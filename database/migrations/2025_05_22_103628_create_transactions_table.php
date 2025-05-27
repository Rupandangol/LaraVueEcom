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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->datetime('date_time');
            $table->string('description')->nullable();
            $table->integer('debit');
            $table->integer('credit');
            $table->string('tag')->nullable();
            $table->string('status');
            $table->string('channel');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->on('admins')->references('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
