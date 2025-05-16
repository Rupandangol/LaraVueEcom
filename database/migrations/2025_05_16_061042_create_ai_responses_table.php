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
        Schema::create('ai_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->text('prompt');
            $table->longText('response');
            $table->text('summary')->nullable();
            $table->string('model')->nullable();
            $table->integer('tokens_used')->nullable();
            $table->string('request_id')->nullable();
            $table->integer('status_code');
            $table->text('error_message')->nullable();

            $table->timestamps();
            $table->foreign('admin_id')->on('admins')->references('id')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_responses');
    }
};
