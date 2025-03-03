<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringDailySchedule extends Model
{
    use HasFactory;

    protected $table = 'recurring_daily_schedules';

    protected $fillable = [
        'title',
        'description',
        'date',
        'start_time',
        'end_time',
        'is_all_day',
        'location',
        'status',
    ];
}
