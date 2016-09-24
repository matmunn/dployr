<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixDeploymentsTable extends Migration
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
            $table->renameColumn('commit', 'commit_hash');
            $table->text('commit_message')->before('started_at')->nullable();
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
            $table->renameColumn('commit_hash', 'commit');
            $table->dropColumn('commit_message');
        });
    }
}
