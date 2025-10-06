<?php

namespace App\Http\Controllers;

use App\DTOs\LogDto;
use App\Enums\LogLevel;
use App\Enums\LogSource;
use App\Services\LogIngestFactory;
use Illuminate\Http\Request;

class SystemLogController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required'],
            'context' => ['sometimes'],
        ]);
        $logDto = new LogDto(
            level: LogLevel::INFO,
            message: $validated['message'],
            context: $validated['context'],
            source: LogSource::API_GATEWAY,
        );
        $logIngest = LogIngestFactory::make('system-log');
        $logIngest->log($logDto);
        return response()->json([
            'status' => 'success',
            'message' => 'created successfully'
        ]);
    }
}
