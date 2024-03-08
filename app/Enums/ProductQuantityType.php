<?php

namespace App\Enums;

enum ProductQuantityType: string
{
    case TYPE_INCREMENT = 'increment';
    case TYPE_DECREMENT = 'decrement';
}
