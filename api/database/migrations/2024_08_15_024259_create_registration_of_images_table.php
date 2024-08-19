<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationOfImagesTable extends Migration
{
    public function up()
    {
        Schema::create('registration_of_images', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('folder');
            $table->text('url');
            $table->timestamp('removed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('registration_of_images');
    }
}