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
        Schema::connection('sqlite_logs')->create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->string('level');
            $table->text('message');
            $table->text('context')->nullable();
            $table->string('source')->nullable();
            $table->timestamps();
            $table->index(['level', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sqlite_logs')->dropIfExists('system_logs');
    }
};
