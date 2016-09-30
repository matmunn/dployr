<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFtpServerDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('servers', function($table)
        {
            $table->integer('server_port')->default(21)->after('server_name');
            $table->integer('server_timeout')->default(90);
            $table->boolean('server_passive')->default(true);
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

        Schema::table('servers', function($table)
        {
            $table->dropColumn('server_port');
            $table->dropColumn('server_timeout');
            $table->dropColumn('server_passive');
        });
    }
}
