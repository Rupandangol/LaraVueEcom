<?php

namespace App\Providers;

use App\Interfaces\ReportGeneratorInterface;
use App\Services\CsvReportGenerator;
use Illuminate\Support\ServiceProvider;

class ReportGeneratorProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ReportGeneratorInterface::class,
            CsvReportGenerator::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
