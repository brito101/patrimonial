<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MaterialRequest;
use App\Models\Department;
use App\Models\Group;
use App\Models\Material;
use App\Models\Views\Group as ViewsGroup;
use App\Models\Views\Material as ViewsMaterial;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Factory|Application|JsonResponse
    {
        CheckPermission::checkAuth('Listar Materiais');

        if ($request->ajax()) {
            $groups = Group::get();

            $token = csrf_token();

            return Datatables::of($groups)
                ->addIndexColumn()
                ->addColumn('description', function ($row) {
                    return Str::limit($row->description);
                })
                ->rawColumns(['description'])
                ->make(true);
        }

        return view('admin.materials.index');
    }

    public function active(Request $request): View|Factory|Application|JsonResponse
    {
        CheckPermission::checkAuth('Listar Materiais');

        if ($request->ajax()) {
            $materials = ViewsMaterial::select('id', 'registration', 'description', 'group_name', 'department_name', 'value')->where('status', 'Ativo')->get();

            $token = csrf_token();

            return Datatables::of($materials)
                ->addIndexColumn()
                ->addColumn('description', function ($row) {
                    return Str::limit($row->description);
                })
                ->addColumn('action', function ($row) use ($token) {
                    return '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="' . route('admin.materials.edit', ['material' => $row->id]) . '"><i class="fa fa-lg fa-fw fa-pen"></i></a>' .
                        '<form method="POST" action="' . route('admin.materials.destroy', ['material' => $row->id]) . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste material?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                })
                ->rawColumns(['description', 'action'])
                ->make(true);
        }

        return view('admin.materials.active');
    }

    public function writeOff(Request $request): View|Factory|Application|JsonResponse
    {
        CheckPermission::checkAuth('Listar Materiais');

        if ($request->ajax()) {
            $materials = ViewsMaterial::select('id', 'registration', 'description', 'group_name', 'department_name', 'value')->where('status', 'Baixa')->get();

            $token = csrf_token();

            return Datatables::of($materials)
                ->addIndexColumn()
                ->addColumn('description', function ($row) {
                    return Str::limit($row->description);
                })
                ->addColumn('action', function ($row) use ($token) {
                    return '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="' . route('admin.materials.edit', ['material' => $row->id]) . '"><i class="fa fa-lg fa-fw fa-pen"></i></a>' .
                        '<form method="POST" action="' . route('admin.materials.destroy', ['material' => $row->id]) . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste material?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                })
                ->rawColumns(['description', 'action'])
                ->make(true);
        }

        return view('admin.materials.write-off');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|\Illuminate\Foundation\Application|View
     */
    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        CheckPermission::checkAuth('Criar Materiais');

        $groups = Group::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('admin.materials.create', compact('groups', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DepartmentRequest $request
     * @return RedirectResponse
     */
    public function store(MaterialRequest $request): RedirectResponse
    {
        CheckPermission::checkAuth('Criar Materiais');

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        $material = Material::create($data);

        if ($material->save()) {
            return redirect()
                ->route('admin.materials.index')
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
     *
     * @param string $id
     * @return Application|Factory|\Illuminate\Foundation\Application|View
     */
    public function edit(string $id): View|\Illuminate\Foundation\Application|Factory|Application
    {
        CheckPermission::checkAuth('Editar Materiais');

        $material = Material::find($id);
        if (!$material) {
            abort(403, 'Acesso não autorizado');
        }

        $groups = Group::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('admin.materials.edit', compact('material', 'groups', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MaterialRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(MaterialRequest $request, string $id): RedirectResponse
    {
        CheckPermission::checkAuth('Editar Materiais');

        $material = Material::find($id);
        if (!$material) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();

        if ($material->update($data)) {
            return redirect()
                ->route('admin.materials.index')
                ->with('success', 'Atualização realizada!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        CheckPermission::checkAuth('Excluir Grupos');

        $material = Material::find($id);
        if (!$material) {
            abort(403, 'Acesso não autorizado');
        }

        if ($material->delete()) {
            return redirect()
                ->route('admin.materials.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
