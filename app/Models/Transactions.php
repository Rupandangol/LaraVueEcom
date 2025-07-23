<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Transactions extends Model
{
    use HasFactory;
    use Searchable;

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
