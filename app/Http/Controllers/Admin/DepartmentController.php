<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DepartmentRequest;
use App\Models\Department;
use App\Models\Views\Department as ViewsDepartment;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use DataTables;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Factory|Application|JsonResponse
    {
        CheckPermission::checkAuth('Listar Setores');

        if ($request->ajax()) {
            $departments = ViewsDepartment::all('id', 'code', 'name');

            $token = csrf_token();

            return Datatables::of($departments)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    return '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="departments/' . $row->id . '/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>' .
                        '<form method="POST" action="departments/' . $row->id . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste setor?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.departments.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|\Illuminate\Foundation\Application|View
     */
    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        CheckPermission::checkAuth('Criar Setores');

        return view('admin.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DepartmentRequest $request
     * @return RedirectResponse
     */
    public function store(DepartmentRequest $request): RedirectResponse
    {
        CheckPermission::checkAuth('Criar Setores');

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        $department = Department::create($data);

        if ($department->save()) {
            return redirect()
                ->route('admin.departments.index')
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
        CheckPermission::checkAuth('Editar Setores');

        $department = Department::find($id);
        if (!$department) {
            abort(403, 'Acesso não autorizado');
        }

        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DepartmentRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(DepartmentRequest $request, string $id): RedirectResponse
    {
        CheckPermission::checkAuth('Editar Setores');

        $department = Department::find($id);
        if (!$department) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();

        if ($department->update($data)) {
            return redirect()
                ->route('admin.departments.index')
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
        CheckPermission::checkAuth('Excluir Setores');

        $department = Department::find($id);
        if (!$department) {
            abort(403, 'Acesso não autorizado');
        }

        if ($department->delete()) {
            return redirect()
                ->route('admin.departments.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
