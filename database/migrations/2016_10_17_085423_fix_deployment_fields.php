<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixDeploymentFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('deployments', function($table)
        {
            $table->dropColumn('repository_id');
            $table->integer('server_id')->after('finished_at');
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
        Schema::table('deployments', function($table)
        {
            $table->dropColumn('server_id');
            $table->integer('repository_id')->after('finished_at');
        });
    }
}
