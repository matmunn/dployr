<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->integer('environment_id');
            $table->text('server_name')->nullable();
            $table->integer('server_port')->default(21);
            $table->text('server_username')->nullable();
            $table->text('server_password')->nullable();
            $table->text('server_path')->nullable();
            $table->integer('server_timeout')->default(90);
            $table->boolean('server_passive')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('servers');
    }
}
