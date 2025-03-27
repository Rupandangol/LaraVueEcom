<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weather_data', function (Blueprint $table) {
            $table->id();
            $table->dateTimeTz('weather_date_time');
            $table->string('weather');
            $table->string('summary');
            $table->string('temperature');
            $table->string('wind_speed');
            $table->string('wind_dir');
            $table->string('wind_angle');
            $table->string('cloud_cover_total');
            $table->string('precipitation_total');
            $table->string('precipitation_type');
            $table->unsignedBigInteger('weather_location_id');
            $table->timestamps();

            $table->foreign('weather_location_id')->on('weather_locations')->references('id')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_data');
    }
};
