<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'lat',
        'lon',
        'units',
        'timezone',
        'elevation'
    ];
}
