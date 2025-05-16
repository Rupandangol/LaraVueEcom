<?php

namespace App\Dto;

class AiReponseDto
{
    public function __construct(
        public readonly string $prompt,
        public readonly string $response,
        public readonly string $model,
        public readonly int $token_used,
        public readonly int $status_code
    ) {}
}
