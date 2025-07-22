<?php

namespace App\Jobs;

use App\DTOs\LogDto;
use App\Services\LogIngestService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogIngestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public LogDto $log_dto)
    {
        $this->log_dto = $log_dto;
    }

    /**
     * Execute the job.
     */
    public function handle(LogIngestService $logIngestService): void
    {
        $logIngestService->log($this->log_dto);
    }
}
