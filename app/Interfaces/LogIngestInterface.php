<?php

namespace App\Interfaces;

use App\DTOs\LogDto;

interface LogIngestInterface
{
    public function log(LogDto $log);
}
