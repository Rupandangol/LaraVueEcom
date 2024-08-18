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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->enum('status', [
                'enable',
                'disable'
            ]);
            $table->boolean('comment_enabled')->default(1);
            $table->boolean('is_featured')->default(0);
            $table->string('title');
            $table->string('slug');
            $table->text('content');
            $table->unsignedBigInteger('blog_category_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('user_id')->on('users')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('blog_category_id')->on('blog_categories')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
