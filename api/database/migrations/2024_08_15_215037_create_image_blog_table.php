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
        if (!Schema::hasTable('image_blog')) {
            Schema::create('image_blog', function (Blueprint $table) {
                $table->id();
                $table->foreignId('image_id')->constrained('registration_of_images')->onDelete('cascade');
                $table->foreignId('blog_post_id')->constrained('blog_post')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('image_blog')) {
            Schema::dropIfExists('image_blog');
        }
    }
};
