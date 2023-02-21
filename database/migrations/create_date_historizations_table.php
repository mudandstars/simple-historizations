<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mudandstars\HistorizeModelChanges\Models\TraitTestModel;

return new class extends Migration
{
    public function up()
    {
        Schema::create('date_historizations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TraitTestModel::class)->constrained();
            $table->date('previous_date')->nullable();
            $table->date('new_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('date_historizations');
    }
};
