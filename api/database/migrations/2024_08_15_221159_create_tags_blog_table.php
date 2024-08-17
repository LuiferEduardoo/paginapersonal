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
        if (!Schema::hasTable('tags_blog')) {
            Schema::create('tags_blog', function (Blueprint $table) {
                $table->id();
                $table->foreignId('blog_post_id')->constrained('blog_post')->onDelete('cascade');
                $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tags_blog')) {
            Schema::dropIfExists('tags_blog');
        }
    }
};
