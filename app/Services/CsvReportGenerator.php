<?php

namespace App\Services;

use App\Interfaces\ReportGeneratorInterface;
use Illuminate\Support\Facades\DB;

class CsvReportGenerator implements ReportGeneratorInterface
{
    public function generate(string $tableName, array $fields)
    {
        return function () use ($tableName, $fields) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $fields, ',');
            DB::table($tableName)
                ->select($fields)
                ->orderBy('id', 'desc')
                ->chunk(500, function ($items) use ($handle) {
                    foreach ($items as $item) {
                        fputcsv($handle, collect($item)->toArray(), ',');
                    }
                });
            fclose($handle);
        };
    }
}
