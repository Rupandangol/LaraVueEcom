<?php

namespace App\Services;

use App\Interfaces\LogIngestInterface;

class LogIngestFactory
{
    public static function make(string $type): LogIngestInterface
    {
        return match (strtolower($type)) {
            'log' => new LogIngestService(),
            'system-log' => new LogIngestServiceSqlite(),
            default => throw new \Exception("Log Ingest [$type] not supported")
        };
    }
}
