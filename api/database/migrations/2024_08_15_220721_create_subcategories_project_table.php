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
        if (!Schema::hasTable('subcategories_project')) {
            Schema::create('subcategories_project', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subcategory_id')->constrained('subcategories')->onDelete('cascade');
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
        if (Schema::hasTable('subcategories_project')) {
            Schema::dropIfExists('subcategories_project');
        }
    }
};
