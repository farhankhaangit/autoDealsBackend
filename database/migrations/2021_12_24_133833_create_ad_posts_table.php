<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_posts', function (Blueprint $table) {
            $table->id();
            $table->string('posted_by');
            $table->string('brand');
            $table->string('name');
            $table->string('variant');
            $table->string('model');
            $table->string('assembly');
            $table->string('engine_size');
            $table->string('color');
            $table->string('fuel_type');
            $table->string('transmission');
            $table->string('milage');
            $table->string('registration_city');
            $table->text('discription');
            $table->string('contact');
            $table->string('location');
            $table->string('Price');
            $table->string('title_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_posts');
    }
}
