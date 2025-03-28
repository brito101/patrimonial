<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MaterialBatchRequest;
use App\Http\Requests\Admin\MaterialRequest;
use App\Imports\MaterialsImport;
use App\Models\Department;
use App\Models\Group;
use App\Models\Material;
use App\Models\Views\Material as ViewsMaterial;
use DataTables;
use Excel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function active(Request $request, $departmentId = null): View|Factory|Application|JsonResponse
    {
        CheckPermission::checkAuth('Listar Materiais');

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $departments = Department::orderBy('name')->get(['id', 'name']);
        } else {
            $departments = Auth::user()->departments()->get(['id', 'name']);
        }

        if ($departmentId) {
            $department = Department::whereIn('id', $departments->pluck('id')->toArray())->where('id', $departmentId)->first();
            if (! $department) {
                abort('403', 'Acesso não autorizado');
            }
        }

        if ($request->ajax()) {
            if ($departmentId) {

                if (Auth::user()->hasRole('Programador|Administrador')) {
                    $materials = ViewsMaterial::select('id', 'department_id', 'secondary_code', 'registration', 'description', 'group_name', 'department_name', 'value', 'year')
                        ->where('department_id', $departmentId)
                        ->where('status', 'Ativo')->get();
                } else {
                    $materials = ViewsMaterial::whereIn('department_id', Auth::user()->departments->pluck('id')->toArray())
                        ->select('id', 'department_id', 'secondary_code', 'registration', 'description', 'group_name', 'department_name', 'value', 'year')
                        ->where('department_id', $departmentId)
                        ->where('status', 'Ativo')->get();
                }

                $token = csrf_token();

                return Datatables::of($materials)
                    ->addIndexColumn()
                    ->addColumn('description', function ($row) {
                        return Str::limit($row->description);
                    })
                    ->addColumn('action', function ($row) use ($token) {
                        return '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="'.route('admin.materials.edit', ['material' => $row->id]).'"><i class="fa fa-lg fa-fw fa-pen"></i></a>'.
                            '<form method="POST" action="'.route('admin.materials.destroy', ['material' => $row->id]).'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste material?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                    })
                    ->rawColumns(['description', 'action'])
                    ->make(true);
            } else {
                if (Auth::user()->hasRole('Programador|Administrador')) {
                    $materials = ViewsMaterial::select('id', 'secondary_code', 'registration', 'description', 'group_name', 'department_name', 'value', 'year')->where('status', 'Ativo')->get();
                } else {
                    $materials = ViewsMaterial::whereIn('department_id', Auth::user()->departments->pluck('id')->toArray())->select('id', 'secondary_code', 'registration', 'description', 'group_name', 'department_name', 'value', 'year')->where('status', 'Ativo')->get();
                }

                $token = csrf_token();

                return Datatables::of($materials)
                    ->addIndexColumn()
                    ->addColumn('description', function ($row) {
                        return Str::limit($row->description);
                    })
                    ->addColumn('action', function ($row) use ($token) {
                        return '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="'.route('admin.materials.edit', ['material' => $row->id]).'"><i class="fa fa-lg fa-fw fa-pen"></i></a>'.
                            '<form method="POST" action="'.route('admin.materials.destroy', ['material' => $row->id]).'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste material?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                    })
                    ->rawColumns(['description', 'action'])
                    ->make(true);
            }
        }

        $departmentName = $departmentId ? $department->name : Auth::user()->departmentsName();

        return view('admin.materials.active', compact('departments', 'departmentId', 'departmentName'));
    }

    public function writeOff(Request $request, $departmentId = null): View|Factory|Application|JsonResponse
    {
        CheckPermission::checkAuth('Listar Materiais');

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $departments = Department::orderBy('name')->get(['id', 'name']);
        } else {
            $departments = Auth::user()->departments()->get(['id', 'name']);
        }

        if ($departmentId) {
            $department = Department::whereIn('id', $departments->pluck('id')->toArray())->where('id', $departmentId)->first();
            if (! $department) {
                abort('403', 'Acesso não autorizado');
            }
        }

        if ($request->ajax()) {
            if ($departmentId) {
                if (Auth::user()->hasRole('Programador|Administrador')) {
                    $materials = ViewsMaterial::select('id', 'department_id', 'secondary_code', 'registration', 'description', 'group_name', 'department_name', 'value', 'year')
                    > where('department_id', $departmentId)
                        ->where('status', 'Baixa')->get();
                } else {
                    $materials = ViewsMaterial::whereIn('department_id', Auth::user()->departments->pluck('id')->toArray())
                        ->select('id', 'department_id', 'secondary_code', 'registration', 'description', 'group_name', 'department_name', 'value', 'year')
                    > where('department_id', $departmentId)
                        ->where('status', 'Baixa')->get();
                }

                $token = csrf_token();

                return Datatables::of($materials)
                    ->addIndexColumn()
                    ->addColumn('description', function ($row) {
                        return Str::limit($row->description);
                    })
                    ->addColumn('action', function ($row) use ($token) {
                        return '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="'.route('admin.materials.edit', ['material' => $row->id]).'"><i class="fa fa-lg fa-fw fa-pen"></i></a>'.
                            '<form method="POST" action="'.route('admin.materials.destroy', ['material' => $row->id]).'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste material?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                    })
                    ->rawColumns(['description', 'action'])
                    ->make(true);
            } else {
                if (Auth::user()->hasRole('Programador|Administrador')) {
                    $materials = ViewsMaterial::select('id', 'secondary_code', 'registration', 'description', 'group_name', 'department_name', 'value', 'year')->where('status', 'Baixa')->get();
                } else {
                    $materials = ViewsMaterial::whereIn('department_id', Auth::user()->departments->pluck('id')->toArray())->select('id', 'secondary_code', 'registration', 'description', 'group_name', 'department_name', 'value', 'year')->where('status', 'Baixa')->get();
                }

                $token = csrf_token();

                return Datatables::of($materials)
                    ->addIndexColumn()
                    ->addColumn('description', function ($row) {
                        return Str::limit($row->description);
                    })
                    ->addColumn('action', function ($row) use ($token) {
                        return '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="'.route('admin.materials.edit', ['material' => $row->id]).'"><i class="fa fa-lg fa-fw fa-pen"></i></a>'.
                            '<form method="POST" action="'.route('admin.materials.destroy', ['material' => $row->id]).'" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="'.$token.'"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste material?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                    })
                    ->rawColumns(['description', 'action'])
                    ->make(true);
            }
        }

        $departmentName = $departmentId ? $department->name : Auth::user()->departmentsName();

        return view('admin.materials.write-off', compact('departments', 'departmentId', 'departmentName'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        CheckPermission::checkAuth('Criar Materiais');

        $groups = Group::orderBy('name')->get();

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $departments = Department::orderBy('name')->get();
        } else {
            $departments = Department::where('id', Auth::user()->department_id)->get();
        }

        return view('admin.materials.create', compact('groups', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  DepartmentRequest  $request
     */
    public function store(MaterialRequest $request): RedirectResponse
    {
        CheckPermission::checkAuth('Criar Materiais');

        $data = $request->all();

        if (Auth::user()->hasRole('Usuário')) {
            $department = Department::where('id', $request->department_id)->where('id', Auth::user()->department_id)->first();
            if (! $department) {
                abort(403, 'Acesso não autorizado');
            }
        }

        $data['user_id'] = Auth::user()->id;

        if ($request->quantity > 1) {
            $rm = (int) $data['registration'];

            for ($i = 0; $i < $request->quantity; $i++) {
                $checkMaterial = Material::where('registration', $rm)->first();
                if ($checkMaterial) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', 'Há um RM ocupado ('.$rm.') na sequência. Foram registrados '.$i++.' materiais.');
                }
                $material = new Material;
                $material->registration = $rm;
                $material->secondary_code = $data['secondary_code'];
                $material->serial_number = $data['serial_number'] ?? null;
                $material->description = $data['description'];
                $material->observations = $data['observations'];
                $material->value = $data['value'];
                $material->group_id = $data['group_id'];
                $material->department_id = $data['department_id'];
                $material->status = $data['status'];
                $material->year = $data['year'];
                $material->user_id = $data['user_id'];
                $material->write_off_date_at = ($data['status'] == 'Baixa' ? date('Y-m-d H:i:s') : null);
                $material->save();
                $rm++;
            }
        } else {
            $material = Material::create($data);
        }

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
     */
    public function edit(string $id): View|\Illuminate\Foundation\Application|Factory|Application
    {
        CheckPermission::checkAuth('Editar Materiais');

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $material = Material::find($id);
        } else {
            $material = Material::whereIn('department_id', Auth::user()->departments->pluck('id')->toArray())->where('id', $id)->first();
        }

        if (! $material) {
            abort(403, 'Acesso não autorizado');
        }

        $groups = Group::orderBy('name')->get();

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $departments = Department::orderBy('name')->get();
        } else {
            $departments = Department::where('id', Auth::user()->department_id)->get();
        }

        return view('admin.materials.edit', compact('material', 'groups', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MaterialRequest $request, string $id): RedirectResponse
    {
        CheckPermission::checkAuth('Editar Materiais');

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $material = Material::find($id);
        } else {
            $material = Material::whereIn('department_id', Auth::user()->departments->pluck('id')->toArray())->where('id', $id)->first();
        }

        if (! $material) {
            abort(403, 'Acesso não autorizado');
        }

        if (Auth::user()->hasRole('Usuário')) {
            $department = Department::where('id', $request->department_id)->where('id', Auth::user()->department_id)->first();
            if (! $department) {
                abort(403, 'Acesso não autorizado');
            }
        }

        $data = $request->all();
        $data['write_off_date_at'] = ($request->status == 'Baixa' ? date('Y-m-d H:i:s') : null);

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
     */
    public function destroy(string $id): RedirectResponse
    {
        CheckPermission::checkAuth('Excluir Materiais');

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $material = Material::find($id);
        } else {
            $material = Material::whereIn('department_id', Auth::user()->departments->pluck('id')->toArray())->where('id', $id)->first();
        }

        if (! $material) {
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

    public function batchWriteOff(Request $request)
    {
        if (! Auth::user()->hasPermissionTo('Editar Materiais')) {
            abort(403, 'Acesso não autorizado');
        }

        if (! $request->ids) {
            return redirect()
                ->back()
                ->with('error', 'Selecione ao menos uma linha!');
        }

        $ids = explode(',', $request->ids);

        foreach ($ids as $id) {
            if (Auth::user()->hasRole('Programador|Administrador')) {
                $material = $material = Material::where('secondary_code', $id)->where('status', 'Ativo')->first();
            } else {
                $material = Material::whereIn('department_id', Auth::user()->departments->pluck('id')->toArray())->where('secondary_code', $id)->where('status', 'Ativo')->first();
            }

            if (! $material) {
                abort(403, 'Acesso não autorizado');
            }

            $material->status = 'Baixa';
            $material->write_off_date_at = date('Y-m-d H:i:s');
            $material->update();
        }

        return redirect()
            ->back()
            ->with('success', 'Materiais atualizados para baixa com sucesso!');
    }

    public function batchActive(Request $request)
    {
        if (! Auth::user()->hasPermissionTo('Editar Materiais')) {
            abort(403, 'Acesso não autorizado');
        }

        if (! $request->ids) {
            return redirect()
                ->back()
                ->with('error', 'Selecione ao menos uma linha!');
        }

        $ids = explode(',', $request->ids);

        foreach ($ids as $id) {

            if (Auth::user()->hasRole('Programador|Administrador')) {
                $material = $material = Material::where('secondary_code', $id)->where('status', 'Baixa')->first();
            } else {
                $material = Material::whereIn('department_id', Auth::user()->departments->pluck('id')->toArray())->where('secondary_code', $id)->where('status', 'Baixa')->first();
            }

            if (! $material) {
                abort(403, 'Acesso não autorizado');
            }

            $material->status = 'Ativo';
            $material->write_off_date_at = null;
            $material->update();
        }

        return redirect()
            ->back()
            ->with('success', 'Materiais atualizados para ativo com sucesso!');
    }

    public function batchDelete(Request $request)
    {
        if (! Auth::user()->hasPermissionTo('Excluir Materiais')) {
            abort(403, 'Acesso não autorizado');
        }

        if (! $request->ids) {
            return redirect()
                ->back()
                ->with('error', 'Selecione ao menos uma linha!');
        }

        $ids = explode(',', $request->ids);

        foreach ($ids as $id) {
            if (Auth::user()->hasRole('Programador|Administrador')) {
                $material = $material = Material::where('secondary_code', $id)->first();
            } else {
                $material = Material::whereIn('department_id', Auth::user()->departments->pluck('id')->toArray())->where('secondary_code', $id)->first();
            }

            if (! $material) {
                abort(403, 'Acesso não autorizado');
            }

            $material->delete();
        }

        return redirect()
            ->back()
            ->with('success', 'Materiais excluídos com sucesso!');
    }

    public function batchEdit(Request $request)
    {
        if (! Auth::user()->hasPermissionTo('Editar Materiais')) {
            abort(403, 'Acesso não autorizado');
        }

        if (! $request->ids) {
            return redirect()
                ->back()
                ->with('error', 'Selecione ao menos uma linha!');
        }

        $ids = explode(',', $request->ids);

        $materials = [];

        foreach ($ids as $id) {
            if (Auth::user()->hasRole('Programador|Administrador')) {
                $material = $material = Material::where('secondary_code', $id)->first();
            } else {
                $material = Material::whereIn('department_id', Auth::user()->departments->pluck('id')->toArray())->where('secondary_code', $id)->first();
            }

            if (! $material) {
                abort(403, 'Acesso não autorizado');
            }

            $materials[] = $material->id;
        }

        $material = Material::find($materials[0]);

        $groups = Group::orderBy('name')->get();

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $departments = Department::orderBy('name')->get();
        } else {
            $departments = Department::where('id', Auth::user()->department_id)->get();
        }

        return view('admin.materials.batch-edit', compact('materials', 'material', 'groups', 'departments'));
    }

    public function batchUpdate(MaterialBatchRequest $request)
    {
        if (! Auth::user()->hasPermissionTo('Excluir Materiais')) {
            abort(403, 'Acesso não autorizado');
        }

        if (! $request->ids) {
            return redirect()
                ->back()
                ->with('error', 'Selecione ao menos uma linha!');
        }

        $data = $request->only(['description', 'observations', 'value', 'group_id', 'department_id', 'status', 'year']);

        $ids = explode(',', $request->ids);

        foreach ($ids as $id) {
            if (Auth::user()->hasRole('Programador|Administrador')) {
                $material = $material = Material::where('id', $id)->first();
            } else {
                $material = Material::whereIn('department_id', Auth::user()->departments->pluck('id')->toArray())->where('id', $id)->first();
            }

            if (! $material) {
                abort(403, 'Acesso não autorizado');
            }

            $material->update($data);
        }

        if ($data['status'] == 'Ativo') {
            return redirect()
                ->route('admin.materials.active')
                ->with('success', 'Materiais atualizados com sucesso!');
        } else {
            return redirect()
                ->route('admin.materials.write-off')
                ->with('success', 'Materiais atualizados com sucesso!');
        }
    }

    public function fileImport(Request $request)
    {
        if (! Auth::user()->hasPermissionTo('Editar Materiais')) {
            abort(403, 'Acesso não autorizado');
        }

        if (! $request->file()) {
            return redirect()
                ->back()
                ->with('error', 'Nenhum arquivo selecionado!');
        }

        Excel::import(new MaterialsImport, $request->file('file')->store('temp'));

        return back()->with('success', 'Importação realizada!');
    }
}
