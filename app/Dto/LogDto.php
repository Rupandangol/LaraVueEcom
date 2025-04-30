<?php

namespace App\Dto;

use App\Enums\LogLevel;
use App\Enums\LogSource;

class LogDto
{
    public function __construct(
        public readonly LogLevel $level,
        public readonly string $message,
        public readonly string $context,
        public readonly LogSource $source
    ) {}
}
