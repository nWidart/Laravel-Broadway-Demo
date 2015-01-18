<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateEventStoreTable extends Migration
{
    public function up()
    {
        Schema::create('event_store', function($table)
        {
            $table->string('uuid', 255);
            $table->string('playhead', 255);
            $table->string('metadata', 255);
            $table->string('payload', 255);
            $table->dateTime('recorded_on');
            $table->string('type', 255);
        });
    }

    public function down()
    {
        Schema::drop('event_store');
    }
}
