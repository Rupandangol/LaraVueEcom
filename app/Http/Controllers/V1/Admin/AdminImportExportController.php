<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminImportExportController extends Controller
{
    protected array $header = [
        'id',
        'name',
        'email',
        'created_at',
    ];

    public function export()
    {
        try {
            $filename = 'admin'.Carbon::now()->format('YmdHis').'.csv';

            return response()->stream(function () {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, $this->header, ',');
                $this->getExportData($handle);
                fclose($handle);
            }, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ]);
        } catch (\Exception $e) {
        }
    }

    public function getExportData($handle)
    {
        DB::table('admins')->orderBy('id', 'desc')->chunk(100, function ($items) use ($handle) {
            foreach ($items as $item) {
                fputcsv($handle, [
                    $item->id ?? '',
                    $item->name ?? '',
                    $item->email ?? '',
                    $item->created_at ? Carbon::parse($item->created_at)->format('Y-m-d') : '',
                ]);
            }
        });
    }

    public function import(Request $request)
    {
        $filepath = public_path('log/admins.csv');
        if (! file_exists($filepath)) {
            return response()->json([
                'status' => 'error',
                'message' => 'File Not found',
            ], 404);
        }
        $handle = fopen($filepath, 'r');
        DB::beginTransaction();
        try {

            $firstRow = true;
            $badRow = [];
            $DataOrientation = ['name', 'email'];
            $nowDate = Carbon::now()->format('Y-m-d H:i:s');
            $password = Hash::make('test');
            while (($row = fgetcsv($handle, 0, ',')) != false) {
                if ($firstRow) {
                    $firstRow = false;
                    if ($DataOrientation != array_map('strtolower', $row)) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Column field or Orientation invalid, needs to be name,email',
                        ]);
                    }

                    continue;
                }
                if (DB::table('admins')->where('name', $row[0])->orWhere('email', $row[1])->first()) {
                    $badRow[] = [
                        'name' => $row[0],
                        'email' => $row[1],
                        'remark' => 'Already Exists',
                    ];

                    continue;
                }
                DB::table('admins')->insert([
                    'name' => $row[0] ?? '',
                    'email' => $row[1] ?? '',
                    'password' => $row[2] ?? $password ?? '',
                    'created_at' => $nowDate,
                    'updated_at' => $nowDate,
                ]);
            }
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'import successful',
                'error_row' => $badRow,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        } finally {
            if (is_resource($handle)) {
                fclose($handle);
            }
        }
    }
}
