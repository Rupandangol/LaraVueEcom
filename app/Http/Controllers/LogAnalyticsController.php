<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogAnalyticsController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = Log::query();

        if ($request->filled('level')) {
            $query->where('level', $request->get('level'));
        }
        if ($request->filled('message')) {
            $query->where('message', 'like', '%'.$request->get('message').'%');
        }
        if ($request->filled('context')) {
            $query->where('context', 'like', '%'.$request->get('context').'%');
        }
        if ($request->filled('source')) {
            $query->where('source', $request->get('source'));
        }
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->get('start_date'));
        }

        $logs = $query;
        $log_count = (clone $query)->count();
        $top_messages = (clone $query)
            ->select('message', DB::raw('count(*) as total'))
            ->groupBy('message')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        $level_distribution = (clone $query)
            ->select('level', DB::raw('count(*) as total'))
            ->groupBy('level')
            ->orderBy('total', 'desc')
            ->get();

        $errors_over_time = (clone $query)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'fetched successfully',
            'data' => [
                'log_count' => $log_count,
                'level_distribution' => $level_distribution,
                'errors_over_time' => $errors_over_time,
                'top_messages' => $top_messages,
                'logs' => $logs->latest()->paginate(10),
            ],
        ]);
    }
}
