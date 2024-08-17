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
        if (!Schema::hasTable('technologies_projects')) {
            Schema::create('technologies_projects', function (Blueprint $table) {
                $table->id();
                $table->foreignId('projects_id')->constrained('projects')->onDelete('cascade');
                $table->foreignId('technology_id')->constrained('skills')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('technologies_projects')) {
            Schema::dropIfExists('technologies_projects');
        }
    }
};
