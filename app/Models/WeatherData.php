<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherData extends Model
{
    use HasFactory;
    protected $fillable = [
        'weather_date_time',
        'weather',
        'summary',
        'temperature',
        'wind_speed',
        'wind_dir',
        'wind_angle',
        'cloud_cover_total',
        'precipitation_total',
        'precipitation_type',
        'weather_location_id',
    ];
}
