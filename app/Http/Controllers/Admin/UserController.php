<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Imports\UsersImport;
use App\Models\Department;
use App\Models\User;
use App\Models\UserDepartment;
use App\Models\Views\User as ViewsUser;
use DataTables;
use Excel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Image;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Factory|Application|JsonResponse
    {
        CheckPermission::checkAuth('Listar Usuários');

        if ($request->ajax()) {
            if (Auth::user()->hasRole('Programador')) {
                $users = ViewsUser::all('id', 'name', 'email', 'type', 'departments');
            } elseif (Auth::user()->hasRole('Administrador')) {
                $users = ViewsUser::whereIn('type', ['Administrador', 'Usuário'])->get();
            } else {
                $users = null;
            }

            $token = csrf_token();

            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    return '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="users/'.$row->id.'/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>'.
                        '<form method="POST" action="users/'.$row->id.'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste usuário?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        CheckPermission::checkAuth('Criar Usuários');

        if (Auth::user()->hasRole('Programador')) {
            $roles = Role::select(['id', 'name'])->orderBy('id', 'desc')->get();
        } else {
            $roles = Role::where('name', '!=', 'Programador')->select(['id', 'name'])->orderBy('id', 'desc')->get();
        }

        $departments = Department::orderBy('name')->get(['id', 'name']);

        return view('admin.users.create', compact('roles', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        CheckPermission::checkAuth('Criar Usuários');

        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $name = Str::slug(mb_substr($data['name'], 0, 100)).time();
            $data = $this->saveImage($request, $name, $data);
        }

        $user = User::create($data);

        if ($user->save()) {
            if (Auth::user()->hasPermissionTo('Atribuir Perfis')) {

                if (! empty($request->role)) {
                    $user->syncRoles($request->role);
                    $user->save();
                }

                if ($request->departments) {
                    foreach ($request->departments as $department) {
                        $userDept = UserDepartment::create(['user_id' => $user->id, 'department_id' => $department]);
                        $userDept->save();
                    }
                }
            }

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Cadastro realizado!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(?int $id = null): View|\Illuminate\Foundation\Application|Factory|Application
    {
        if ($id) {
            CheckPermission::checkAuth('Editar Usuários');
        } else {
            CheckPermission::checkAuth('Editar Usuário');
            $id = Auth::user()->id;
        }

        $user = User::with('departments')->find($id);
        if (! $user) {
            abort(403, 'Acesso não autorizado');
        }

        if (Auth::user()->hasRole('Programador')) {
            $roles = Role::select(['id', 'name'])->orderBy('id', 'desc')->get();
        } else {
            $roles = Role::where('name', '!=', 'Programador')->select(['id', 'name'])->orderBy('id', 'desc')->get();
        }

        $departments = Department::orderBy('name')->get(['id', 'name']);

        return view('admin.users.edit', compact('user', 'roles', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, int $id): RedirectResponse
    {
        CheckPermission::checkManyAuth(['Editar Usuários', 'Editar Usuário']);

        $data = $request->all();

        if (! Auth::user()->hasPermissionTo('Editar Usuários') && Auth::user()->hasPermissionTo('Editar Usuário')) {
            $user = User::where('id', Auth::user()->id)->first();
        } else {
            $user = User::find($id);
        }

        if (! $user) {
            abort(403, 'Acesso não autorizado');
        }

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($request->password);
        } else {
            $data['password'] = $user->password;
        }

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $name = Str::slug(mb_substr($data['name'], 0, 200)).'-'.time();
            $imagePath = storage_path().'/app/public/users/'.$user->photo;

            if (File::isFile($imagePath)) {
                unlink($imagePath);
            }

            $data = $this->saveImage($request, $name, $data);
        }

        // if (Auth::user()->hasPermissionTo('Atribuir Perfis')) {
        //     $data['department_id'] = $request->department_id;
        // } else {
        //     $data['department_id'] = Auth::user()->department_id;
        // }

        if ($user->update($data)) {

            if (Auth::user()->hasPermissionTo('Atribuir Perfis')) {

                if (! empty($request->role)) {
                    $user->syncRoles($request->role);
                    $user->save();
                }

                $user->userDepartments()->delete();
                if ($request->departments) {
                    foreach ($request->departments as $department) {
                        $userDept = UserDepartment::create(['user_id' => $user->id, 'department_id' => $department]);
                        $userDept->save();
                    }
                }
            }

            if (Auth::user()->hasPermissionTo('Editar Usuários')) {
                return redirect()
                    ->route('admin.users.index')
                    ->with('success', 'Atualização realizada!');
            } else {
                return redirect()
                    ->route('admin.user.edit')
                    ->with('success', 'Atualização realizada!');
            }
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        CheckPermission::checkAuth('Excluir Usuários');

        $user = User::find($id);

        if (! $user) {
            abort(403, 'Acesso não autorizado');
        }

        $imagePath = storage_path().'/app/public/users/'.$user->photo;
        if ($user->delete()) {
            if (File::isFile($imagePath)) {
                unlink($imagePath);
                $user->photo = null;
                $user->update();
            }

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }

    public function fileImport(Request $request)
    {
        if (! Auth::user()->hasPermissionTo('Editar Usuários')) {
            abort(403, 'Acesso não autorizado');
        }

        if (! $request->file()) {
            return redirect()
                ->back()
                ->with('error', 'Nenhum arquivo selecionado!');
        }
        Excel::import(new UsersImport, $request->file('file')->store('temp'));

        return back()->with('success', 'Importação realizada!');
    }

    private function saveImage(UserRequest $request, string $name, array $data): array
    {
        $extension = $request->photo->extension();
        $nameFile = "$name.$extension";

        $data['photo'] = $nameFile;

        $destinationPath = storage_path().'/app/public/users';

        if (! file_exists($destinationPath)) {
            mkdir($destinationPath, 755, true);
        }

        $img = Image::make($request->photo);
        $img->resize(null, 100, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->crop(100, 100)->save($destinationPath.'/'.$nameFile);

        return $data;
    }
}
