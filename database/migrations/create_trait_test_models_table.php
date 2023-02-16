<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trait_test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('string');
            $table->integer('integer')->nullable();
            $table->boolean('boolean')->nullable();
            $table->date('date')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->timestampTz('timestampTz')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trait_test_models');
    }
};
