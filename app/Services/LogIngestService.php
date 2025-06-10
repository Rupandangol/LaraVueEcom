<?php

namespace App\Services;

use App\DTOs\LogDto;
use App\Models\Log;

class LogIngestService
{
    public function log(LogDto $log)
    {
        Log::create([
            'level' => $log->level,
            'message' => $log->message,
            'context' => $log->context,
            'source' => $log->source,
        ]);
    }
}
