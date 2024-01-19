<?php

namespace App\Enums;

enum Status: string
{
    case PENDING = 'pending';
    case DELIVERED = 'delivered';
    case IN_TRANSIT = 'in_transit';
}
