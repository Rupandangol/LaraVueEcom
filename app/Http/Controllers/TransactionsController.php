<?php

namespace App\Http\Controllers;

use App\Imports\TransactionImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PHPUnit\Framework\Constraint\Count;

use function Laravel\Prompts\error;

class TransactionsController extends Controller
{
    public function import(Request $request)
    {
        try {
            $file = public_path('/log/statement.xls');
            if (!file_exists($file)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File doesnt exists'
                ], 404);
            }
            $uploaded = Excel::import(new TransactionImport, $file);
            if ($uploaded) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Imported successfully'
                ], 200);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    public function analytics(Request $request)
    {
        $query = DB::table('transactions');

        if ($request->filled('date')) {
            $query->whereDate('date_time', $request->get('date'));
        }
        $top_expenses = (clone $query)
            ->select('description', DB::raw('COUNT(*) as total'),DB::raw('SUM(debit) as total_spent'))
            ->groupBy('description')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        $total=(clone $query)->count();
        $total_spent=(clone $query)->select(DB::raw('SUM(debit) as sum'))
        ->get();
        return response()->json([
            'status' => 'success',
            'message' => 'fetched Successfully',
            'data' => [
                'total' => $total,
                'total_spent' => $total_spent,
                'top_expenses' => $top_expenses,
                'transactions' => $query->paginate(10),
            ]
        ]);
    }
}
