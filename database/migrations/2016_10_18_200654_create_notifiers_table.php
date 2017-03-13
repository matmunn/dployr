<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotifiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifiers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('environment_id');
            $table->string('type');
            $table->string('data1');
            $table->string('data2')->nullable();
            $table->string('data3')->nullable();
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
        Schema::drop('notifiers');
    }
}
