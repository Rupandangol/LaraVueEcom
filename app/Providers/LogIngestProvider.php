<?php

namespace App\Providers;

use App\Interfaces\LogIngestInterface;
use App\Services\LogIngestService;
use Illuminate\Support\ServiceProvider;

class LogIngestProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // $this->app->bind(LogIngestInterface::class, LogIngestService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {}
}
