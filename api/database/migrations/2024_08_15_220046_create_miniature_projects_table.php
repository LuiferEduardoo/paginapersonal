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
        if (!Schema::hasTable('miniature_projects')) {
            Schema::create('miniature_projects', function (Blueprint $table) {
                $table->id();
                $table->foreignId('image_id')->constrained('registration_of_images')->onDelete('cascade');
                $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('miniature_projects')) {
            Schema::dropIfExists('miniature_projects');
        }
    }
};
