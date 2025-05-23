<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'description',
        'debit',
        'credit',
        'status',
        'channel',
        'admin_id',
    ];
}
