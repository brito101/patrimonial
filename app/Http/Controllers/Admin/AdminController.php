<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use App\Models\Views\Department as ViewsDepartment;
use App\Models\Views\Material as ViewsMaterial;
use App\Models\Views\User as ViewsUser;
use App\Models\Views\Visit;
use App\Models\Views\VisitYesterday;
use DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $programmers = ViewsUser::where('type', 'Programador')->count();
        $administrators = ViewsUser::where('type', 'Administrador')->count();
        $users = ViewsUser::where('type', 'Usuário')->count();

        $departments = ViewsDepartment::count();
        $groups = Group::get();

        $materialChart = new stdClass;
        $materialChart->labels = [];
        $materialChart->materials = ['active' => [
            'quantity' => [],
            'value' => [],
            'year' => [],
        ], 'writeOff' => [
            'quantity' => [],
            'value' => [],
            'year' => [],
        ]];

        foreach ($groups as $group) {
            $materialChart->labels[] = $group->name;
            $materialChart->quantity[] = $group->quantity;
            $materialChart->value[] = (float) str_replace(['R$ ', '.', ','], ['', '', '.'], $group->depreciated_value);
        }

        if (Auth::user()->hasRole('Programador|Administrador')) {
            $materials = ViewsMaterial::get();
        } else {
            $materials = ViewsMaterial::where('department_id', Auth::user()->department_id)->get();
        }

        $groupMaterials = $materials->sortBy('year')->groupBy('year');

        foreach ($groupMaterials as $material) {
            $materialChart->materials['active']['quantity'][] = $material->where('status', 'Ativo')->count();
            $materialChart->materials['active']['year'][] = $material[0]->year;
            $materialChart->materials['writeOff']['quantity'][] = $material->where('status', 'Baixa')->count();
            $materialChart->materials['writeOff']['year'][] = $material[0]->year;
        }

        $visits = Visit::where('url', '!=', route('admin.home.chart'))
            ->where('url', 'NOT LIKE', '%columns%')
            ->where('url', 'NOT LIKE', '%storage%')
            ->where('url', 'NOT LIKE', '%offline%')
            ->where('url', 'NOT LIKE', '%manifest.json%')
            ->where('url', 'NOT LIKE', '%.png%')
            ->get();

        if ($request->ajax()) {
            return Datatables::of($visits)
                ->addColumn('time', function ($row) {
                    return date(('H:i:s'), strtotime($row->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['time'])
                ->make(true);
        }

        /** Statistics */
        $statistics = $this->accessStatistics();
        $onlineUsers = $statistics['onlineUsers'];
        $percent = $statistics['percent'];
        $access = $statistics['access'];
        $chart = $statistics['chart'];

        return view('admin.home.index', compact(
            'programmers',
            'administrators',
            'users',
            'materials',
            'departments',
            'groups',
            'onlineUsers',
            'percent',
            'access',
            'chart',
            'materialChart',
        ));
    }

    public function chart(): JsonResponse
    {
        /** Statistics */
        $statistics = $this->accessStatistics();
        $onlineUsers = $statistics['onlineUsers'];
        $percent = $statistics['percent'];
        $access = $statistics['access'];
        $chart = $statistics['chart'];

        return response()->json([
            'onlineUsers' => $onlineUsers,
            'access' => $access,
            'percent' => $percent,
            'chart' => $chart,
        ]);
    }

    private function accessStatistics(): array
    {
        $onlineUsers = User::online()->count();

        $accessToday = Visit::where('url', '!=', route('admin.home.chart'))
            ->where('url', 'NOT LIKE', '%columns%')
            ->where('url', 'NOT LIKE', '%storage%')
            ->where('url', 'NOT LIKE', '%offline%')
            ->where('url', 'NOT LIKE', '%manifest.json%')
            ->where('url', 'NOT LIKE', '%.png%')
            ->where('method', 'GET')
            ->get();
        $accessYesterday = VisitYesterday::where('url', '!=', route('admin.home.chart'))
            ->where('url', 'NOT LIKE', '%columns%')
            ->where('url', 'NOT LIKE', '%storage%')
            // ->where('url', 'NOT LIKE', '%admin%')
            ->where('url', 'NOT LIKE', '%offline%')
            ->where('url', 'NOT LIKE', '%manifest.json%')
            ->where('url', 'NOT LIKE', '%.png%')
            ->where('method', 'GET')
            ->count();

        $totalDaily = $accessToday->count();

        $percent = 0;
        if ($accessYesterday > 0 && $totalDaily > 0) {
            $percent = number_format((($totalDaily - $accessYesterday) / $totalDaily * 100), 2, ',', '.');
        }

        /** Visitor Chart */
        $data = $accessToday->groupBy(function ($reg) {
            return date('H', strtotime($reg->created_at));
        });

        $dataList = [];
        foreach ($data as $key => $value) {
            $dataList[$key.'H'] = count($value);
        }

        $chart = new stdClass;
        $chart->labels = (array_keys($dataList));
        $chart->dataset = (array_values($dataList));

        return [
            'onlineUsers' => $onlineUsers,
            'access' => $totalDaily,
            'percent' => $percent,
            'chart' => $chart,
        ];
    }
}
