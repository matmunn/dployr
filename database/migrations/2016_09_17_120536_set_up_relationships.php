<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetUpRelationships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('repositories', function($table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('url');
            $table->integer('user_id');
            $table->timestamps();
        });

        Schema::create('environments', function($table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->integer('repository_id');
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
        //
        Schema::drop('repositories');
        Schema::drop('environments');
    }
}
