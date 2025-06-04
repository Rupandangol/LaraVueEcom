<?php

namespace App\Dto;

class QrGeneratorDto
{

    public function __construct(
        public readonly string $url,
        public readonly int $size = 300,
        public readonly string $type = 'png'
    ) {}
}
