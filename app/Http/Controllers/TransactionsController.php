<?php

namespace App\Http\Controllers;

use App\Imports\TransactionImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
}
