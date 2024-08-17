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
        if (!Schema::hasTable('information_contact')) {
            Schema::create('information_contact', function (Blueprint $table) {
                $table->id();
                $table->text('name');
                $table->text('email');
                $table->text('subject');
                $table->text('content');
                $table->text('date');
                $table->text('settled');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('information_contact')) {
            Schema::dropIfExists('information_contact');
        }
    }
};
