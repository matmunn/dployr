<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('servers', function($table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->integer('environment_id');
            $table->timestamps();
            $table->softDeletes();
            $table->text('server_name')->nullable();
            $table->text('server_username')->nullable();
            $table->text('server_password')->nullable();
            $table->text('server_path')->nullable();
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
        Schema::drop('servers');
    }
}
