<?php

namespace App\DTOs;

class CheckQuantityDto
{
    public function __construct(
        public readonly ?int $product_id,
        public readonly int $quantity
    ) {}
}
