<?php

namespace App\Services;

use App\DTOs\LogDto;
use App\Interfaces\LogIngestInterface;
use App\Models\Log;

class LogIngestService implements LogIngestInterface
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
