<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mudandstars\SimpleHistorizations\Models\TraitTestModel;

return new class extends Migration
{
    public function up()
    {
        Schema::create('integer_historizations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TraitTestModel::class)->constrained();
            $table->integer('previous_integer');
            $table->integer('new_integer');
            $table->timestampTz('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('integer_historizations');
    }
};
