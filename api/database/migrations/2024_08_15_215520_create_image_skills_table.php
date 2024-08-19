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
        if (!Schema::hasTable('image_skills')) {
            Schema::create('image_skills', function (Blueprint $table) {
                $table->id();
                $table->foreignId('image_id')->constrained('registration_of_images')->onDelete('cascade');
                $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');
                $table->unique(['image_id', 'skill_id']);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('image_skills')) {
            Schema::dropIfExists('image_skills');
        }
    }
};
