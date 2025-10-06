<?php

namespace App\Services;

use App\DTOs\LogDto;
use App\Interfaces\LogIngestInterface;
use App\Models\SystemLog;

class LogIngestServiceSqlite implements LogIngestInterface
{
    public function log(LogDto $log)
    {
        SystemLog::create([
            'level' => $log->level,
            'message' => $log->message,
            'context' => $log->context,
            'source' => $log->source,
        ]);
    }
}
