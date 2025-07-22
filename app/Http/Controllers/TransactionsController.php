<?php

namespace App\Http\Controllers;

use App\Imports\TransactionImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TransactionsController extends Controller
{
    public function import(Request $request)
    {
        try {
            // $file = public_path('/log/Statement_2025-01-01_to_2025-03-01_2025-05-27_17_25_32.xls');
            $file = public_path('/log/statement1.xls');
            if (! file_exists($file)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File doesnt exists',
                ], 404);
            }
            $uploaded = Excel::import(new TransactionImport, $file);
            if ($uploaded) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Imported successfully',
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function analytics(Request $request)
    {
        $query = DB::table('transactions');
        if ($request->filled('date')) {
            $query->whereDate('date_time', $request->get('date'));
        }
        if ($request->filled('description')) {
            $query->where('description', 'like', '%'.$request->get('description').'%');
        }
        if ($request->filled('month')) {
            $query->whereYear('date_time', Carbon::parse($request->get('month'))->format('Y'))
                ->whereMonth('date_time', Carbon::parse($request->get('month'))->format('m'));
        }
        $top_expenses = (clone $query)
            ->select('description', DB::raw('COUNT(*) as total'), DB::raw('SUM(debit) as total_spent'))
            ->groupBy('description')
            ->orderByDesc('total_spent', 'total')
            ->limit(5)
            ->get();
        $total = (clone $query)->count();
        $total_spent = (clone $query)->select(DB::raw('SUM(debit) as sum'))
            ->get();
        $over_all_forecast = (clone $query)->selectRaw('MONTH(date_time) as month, YEAR(date_time) as year, SUM(debit) as total_spent')
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();
        $three_month_forecast = (clone $query)->selectRaw('MONTH(date_time) as month, YEAR(date_time) as year, SUM(debit) as total_spent')
            ->where('date_time', '>=', Carbon::now()->subMonths(3))
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();
        $over_all_time_based_spendings = (clone $query)
            ->selectRaw('HOUR(date_time) as hour, COUNT(*) as total, SUM(debit) as sum')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'fetched Successfully',
            'data' => [
                'total' => $total,
                'total_spent' => $total_spent,
                'over_all_forecast' => round($over_all_forecast->avg('total_spent')),
                'three_month_forecast' => round($three_month_forecast->avg('total_spent')),
                'over_all_time_based_spendings' => $over_all_time_based_spendings,
                'top_expenses' => $top_expenses,
                'transactions' => $query->paginate(10)->appends($request->only(['description', 'date', 'month'])),
            ],
        ]);
    }
}
