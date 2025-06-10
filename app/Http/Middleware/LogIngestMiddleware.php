<?php

namespace App\Http\Middleware;

use App\DTOs\LogDto;
use App\Enums\LogLevel;
use App\Enums\LogSource;
use App\Jobs\LogIngestJob;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogIngestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $logData = new LogDto(
            level: LogLevel::INFO,
            message: 'Api triggered',
            context: $request->url(),
            source: LogSource::API_GATEWAY
        );
        LogIngestJob::dispatch($logData);
        return $next($request);
    }
}
