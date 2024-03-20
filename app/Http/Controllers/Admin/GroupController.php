<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GroupRequest;
use App\Models\Group;
use App\Models\Views\Group as ViewsGroup;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use DataTables;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Factory|Application|JsonResponse
    {
        CheckPermission::checkAuth('Listar Grupos');

        if ($request->ajax()) {
            $groups = ViewsGroup::all('id', 'code', 'name');

            $token = csrf_token();

            return Datatables::of($groups)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    return '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" href="groups/' . $row->id . '/edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>' .
                        '<form method="POST" action="groups/' . $row->id . '" class="btn btn-xs px-0"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . $token . '"><button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" onclick="return confirm(\'Confirma a exclusão deste grupo?\')"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.groups.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|\Illuminate\Foundation\Application|View
     */
    public function create(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        CheckPermission::checkAuth('Criar Grupos');

        return view('admin.groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DepartmentRequest $request
     * @return RedirectResponse
     */
    public function store(GroupRequest $request): RedirectResponse
    {
        CheckPermission::checkAuth('Criar Grupos');

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        $group = Group::create($data);

        if ($group->save()) {
            return redirect()
                ->route('admin.groups.index')
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
        CheckPermission::checkAuth('Editar Grupos');

        $group = Group::find($id);
        if (!$group) {
            abort(403, 'Acesso não autorizado');
        }

        return view('admin.groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GroupRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(GroupRequest $request, string $id): RedirectResponse
    {
        CheckPermission::checkAuth('Editar Grupos');

        $group = Group::find($id);
        if (!$group) {
            abort(403, 'Acesso não autorizado');
        }

        $data = $request->all();

        if ($group->update($data)) {
            return redirect()
                ->route('admin.groups.index')
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

        $group = Group::find($id);
        if (!$group) {
            abort(403, 'Acesso não autorizado');
        }

        if ($group->delete()) {
            return redirect()
                ->route('admin.groups.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
