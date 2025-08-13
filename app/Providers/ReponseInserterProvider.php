<?php

namespace App\Providers;

use App\Interfaces\ResponseInserterInterface;
use App\Services\AiResponseInserter;
use Illuminate\Support\ServiceProvider;

class ReponseInserterProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ResponseInserterInterface::class, AiResponseInserter::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
