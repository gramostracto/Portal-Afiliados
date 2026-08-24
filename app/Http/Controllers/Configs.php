<?php

namespace App\Http\Controllers;

use App\Http\Helpers\CommonUtils;
use App\Models\PortalSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Configs extends Controller
{
    public function index()
    {
        $setting = DB::table('portal_settings')->get();
        return view('config.config', ['setting' => $setting]);
    }

    public function statistics(Request $request)
    {
        return view('config.statistics');
    }

    public function getDecryptedData(Request $request)
    {
        $var =  CommonUtils::getSetting($request->name);
        return response()->json(['success' => true, 'data' => $var]);
    }

    public function update(Request $request)
    {
        if (!empty($request)) {
            $val = ($request->isEncrypt == 1) ? Crypt::encryptString($request->val) : $request->val;

            $result = DB::table('portal_settings')
                ->where('name', $request->name)
                ->update(['val' => $val]);

            return response()->json(['success' => true, 'data' => $result]);
        }
    }

    public function create()
    {
        return view('config.crear');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'val' => 'required',
        ]);

        PortalSetting::create(['name' => $request->input('name'), 'val' => $request->input('val')]);

        return redirect()->route('setting');
    }

    public function listarAfiliados(Request $request)
    {
        $search = $request->input('q', '');

        if (empty($search) || strlen($search) < 2) {
            $users = DB::table('users')
                ->select('id', 'name', 'number_id')
                ->limit(10)
                ->get();
        } else {
            // Buscar usuarios por nombre o número de identificación
            $users = DB::table('users')
                ->select('id', 'name', 'number_id')
                ->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('number_id', 'like', '%' . $search . '%');
                })
                ->limit(50)
                ->get();
        }

        return response()->json($users);
    }


    public function countActionHome(Request $request)
    {
        $arrayActionInvoice = array();
        $query = DB::table('user_tracking')
            ->select('detail', DB::raw('count(*) as total'))
            ->groupBy('detail')
            ->get();

        foreach ($query as $line) {
            if ($line->detail != "") {
                array_push($arrayActionInvoice, array(
                    'x' => $line->detail,
                    'value' => $line->total
                ));
            }
        }
        return response()->json(['success' => true, 'data' => $arrayActionInvoice]);
    }

    public function configSistem()
    {
        $notificationType = Auth::User()->notifications;
        return view('config.sistema', ['notifications' => $notificationType]);
    }

    public function configSistemModificacion(Request $request)
    {
        $response =  DB::table('users')->where('id', Auth::User()->id)->update(['notifications' => $request->notification]);
        return response()->json(['success' => true, 'data' => $response]);
    }

    public function countLogin(Request $request)
    {
        $request->validate([
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'year' => 'nullable|integer|min:2020|max:' . (date('Y') + 1)
        ]);


        if ($request->startDate && $request->endDate) {
            $startDate = Carbon::parse($request->startDate)->startOfDay();
            $endDate = Carbon::parse($request->endDate)->endOfDay();
        } elseif ($request->year) {
            $startDate = Carbon::create($request->year, 1, 1)->startOfYear();
            $endDate = Carbon::create($request->year, 12, 31)->endOfYear();
        } else {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfDay();
        }

        // Configurar nombres de meses en español
        DB::statement("SET lc_time_names = 'es_ES';");

        // Estadísticas de inicio de sesión
        $loginStats = $this->getLoginStats($startDate, $endDate);

        // Usuarios activos (han iniciado sesión en el período)
        $activeUsers = DB::table('user_tracking')
            ->where('action', 'INICIO SESSION')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct('user_id')
            ->count('user_id');

        // Consultas de facturas
        $invoiceConsultations = DB::table('user_tracking')
            ->where('action', 'CONSULTO FACTURAS')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'totalLogins' => $loginStats['total'],
                'activeUsers' => $activeUsers,
                'invoiceConsultations' => $invoiceConsultations,
                'loginStats' => $loginStats
            ]
        ]);
    }

    protected function getLoginStats($startDate, $endDate)
    {
        // Consulta para obtener total
        $total = DB::table('user_tracking')
            ->where('action', 'INICIO SESSION')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Consulta para obtener por mes
        $monthlyData = DB::table('user_tracking')
            ->select(
                DB::raw('MONTHNAME(created_at) AS month'),
                DB::raw('MONTH(created_at) AS month_number'),
                DB::raw('COUNT(*) AS count')
            )
            ->where('action', 'INICIO SESSION')
            ->whereBetween('user_tracking.created_at', [$startDate, $endDate])
            ->groupBy('month', 'month_number')
            ->orderBy('month_number')
            ->get();

        // Preparar datos para el gráfico
        $months = [];
        $counts = [];

        foreach ($monthlyData as $data) {
            $months[] = ucfirst($data->month);
            $counts[] = $data->count;
        }

        $byUser = DB::table('user_tracking')
            ->leftJoin('users', 'users.id', '=', 'user_tracking.user_id')
            ->select(
                'users.name as user_name',
                DB::raw('COUNT(*) as count')
            )
            ->where('action', 'INICIO SESSION')
            ->whereBetween('user_tracking.created_at', [$startDate, $endDate])
            ->groupBy('users.name')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                return [
                    'name' => $row->user_name ?? '-',
                    'count' => $row->count
                ];
            });

        return [
            'total' => $total,
            'months' => $months,
            'monthlyCounts' => $counts,
            'byUser' => $byUser
        ];
    }

    public function filter(Request $request)
    {
        $request->validate([
            'numberId' => 'nullable|integer|exists:users,id',
            'dateRange' => 'nullable|string',
            'page' => 'nullable|integer|min:1',
            'perPage' => 'nullable|integer|min:1|max:100'
        ]);

        $userId = $request->numberId;

        // Procesar rango de fechas
        if ($request->dateRange) {
            $dates = explode(' - ', $request->dateRange);
            $startDate = isset($dates[0]) ? Carbon::parse($dates[0])->startOfDay() : Carbon::now()->startOfMonth();
            $endDate = isset($dates[1]) ? Carbon::parse($dates[1])->endOfDay() : Carbon::now()->endOfDay();
        } else {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfDay();
        }

        // Obtener estadísticas de actividad
        $activityStats = $this->getUserActivityStats($userId, $startDate, $endDate);

        $page = (int) ($request->page ?? 1);
        $perPage = (int) ($request->perPage ?? 10);

        $baseQuery = DB::table('user_tracking')
            ->leftJoin('users', 'users.id', '=', 'user_tracking.user_id')
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->whereBetween('user_tracking.created_at', [$startDate, $endDate])
            ->select(
                'user_tracking.*',
                'users.name as user_name'
            );

        $totalActions = (clone $baseQuery)->count();

        // Obtener acciones recientes
        $recentActions = $baseQuery
            ->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->created_at)->format('d/m/Y H:i:s'),
                    'type' => $item->action,
                    'detail' => $item->detail ?? '-',
                    'ip' => $item->ip ?? '-',
                    'user' => $item->user_name ?? '-'
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'activityStats' => $activityStats,
                'recentActions' => $recentActions,
                'pagination' => [
                    'page' => $page,
                    'perPage' => $perPage,
                    'total' => $totalActions,
                    'totalPages' => (int) ceil($totalActions / $perPage)
                ]
            ]
        ]);
    }

    protected function getUserActivityStats($userId, $startDate = null, $endDate = null)
    {
        $query = DB::table('user_tracking')
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                return $query->whereBetween('user_tracking.created_at', [$startDate, $endDate]);
            });

        if ($userId) {
            $actions = $query
                ->select(
                    'action',
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->get();

            $labels = [];
            $data = [];

            foreach ($actions as $action) {
                $labels[] = $action->action;
                $data[] = $action->count;
            }

            return [
                'mode' => 'actions',
                'labels' => $labels,
                'data' => $data
            ];
        }

        $users = $query
            ->leftJoin('users', 'users.id', '=', 'user_tracking.user_id')
            ->select(
                'users.name as user_name',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('users.name')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $labels = [];
        $data = [];

        foreach ($users as $user) {
            $labels[] = $user->user_name ?? '-';
            $data[] = $user->count;
        }

        return [
            'mode' => 'users',
            'labels' => $labels,
            'data' => $data
        ];
    }
}
