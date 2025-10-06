<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $connection = 'sqlite_logs';
    protected $table = 'system_logs';
    protected $guarded = [];
}
