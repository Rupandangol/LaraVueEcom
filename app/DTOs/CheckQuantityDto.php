<?php

namespace App\DTOs;

use Ramsey\Uuid\Type\Integer;

class CheckQuantityDto
{
    public function __construct(
        public readonly ?int $product_id,
        public readonly int $quantity
    ) {}
}
