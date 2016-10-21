<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NotifiersFieldsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('notifiers', function($table)
        {
            $table->string('data2')->nullable()->change();
            $table->string('data3')->nullable()->change();
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
        Schema::table('notifiers', function($table)
        {
            $table->string('data2')->nullable(false)->change();
            $table->string('data3')->nullable(false)->change();
        });
    }
}
