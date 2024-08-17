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
        if (!Schema::hasTable('blog_post')) {
            Schema::create('blog_post', function (Blueprint $table) {
                $table->id();
                $table->text('title');
                $table->text('content');
                $table->text('link');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('authors');
                $table->time('reading_time')->nullable();
                $table->text('image_credits');
                $table->boolean('visible')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('blog_post')) {
            Schema::dropIfExists('blog_post');
        }
    }
};
