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
        if (!Schema::hasTable('subcategories_skill')) {
            Schema::create('subcategories_skill', function (Blueprint $table) {
                $table->id();
                $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');
                $table->foreignId('subcategory_id')->constrained('subcategories')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('subcategories_skill')) {
            Schema::dropIfExists('subcategories_skill');
        }
    }
};
