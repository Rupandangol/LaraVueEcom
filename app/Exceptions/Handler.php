<?php

namespace App\Exceptions;

use App\DTOs\LogDto;
use App\Enums\LogLevel;
use App\Enums\LogSource;
use App\Jobs\LogIngestJob;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            $logdata = new LogDto(
                level: LogLevel::ERROR,
                message: $e->getMessage(),
                context: $e->getLine(),
                source: LogSource::EXCEPTION_HANDLER
            );
            LogIngestJob::dispatch($logdata);
        });
    }
}
