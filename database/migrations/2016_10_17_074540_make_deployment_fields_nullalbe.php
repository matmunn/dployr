<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeDeploymentFieldsNullalbe extends Migration
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
            $table->string('commit_hash')->nullable()->change();
            $table->datetime('started_at')->nullable()->change();
            $table->datetime('finished_at')->nullable()->change();
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
            $table->string('commit_hash')->nullable(false)->change();
            $table->datetime('started_at')->nullable(false)->change();
            $table->datetime('finished_at')->nullable(false)->change();
        });
    }
}
