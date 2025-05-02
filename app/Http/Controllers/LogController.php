<?php

namespace App\Http\Controllers;

use App\Dto\LogDto;
use App\Enums\LogLevel;
use App\Enums\LogSource;
use App\Jobs\LogIngestJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function exportLog()
    {
        try {
            $filename = 'log_' . Carbon::now()->format('YmdHis') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];
            return response()->stream(function () {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, [
                    'id',
                    'level',
                    'message',
                    'context',
                    'source'
                ]);
                DB::table('logs')
                    ->select('id', 'level', 'message', 'context', 'source')
                    ->orderBy('id', 'desc')
                    ->chunk(500, function ($items) use ($handle) {
                        foreach ($items as $item) {
                            $data = [
                                isset($item->id) ? $item->id : '',
                                isset($item->level) ? $item->level : '',
                                isset($item->message) ? $item->message : '',
                                isset($item->context) ? $item->context : '',
                                isset($item->source) ? $item->source : '',
                            ];
                            fputcsv($handle, $data);
                        }
                    });
                fclose($handle);
            }, 200, $headers);
        } catch (\Exception $e) {
            $log = new LogDto(
                level: LogLevel::ERROR,
                message: $e->getMessage(),
                context: $e->getLine(),
                source: LogSource::API_GATEWAY
            );
            LogIngestJob::dispatch($log);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
