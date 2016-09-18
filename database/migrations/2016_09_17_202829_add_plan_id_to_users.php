<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlanIdToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('plans', function($table)
        {
            $table->increments('id');
            $table->string('name');
            $table->decimal('price', 5,2);
            $table->integer('repository_limit');
            $table->timestamps();
        });

        Schema::table('users', function($table)
        {
            $table->integer('plan_id')->before('created_at');
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
        Schema::table('users', function($table)
        {
            $table->dropColumn('plan_id');
        });

        Schema::drop('plans');
    }
}
