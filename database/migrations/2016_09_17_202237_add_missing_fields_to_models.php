<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMissingFieldsToModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function($table)
        {
            $table->string('site_name')->before('created_at');
        });

        Schema::table('repositories', function($table)
        {
            $table->string('secret_key')->before('created_at');
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
            $table->dropColumn('site_name');
        });

        Schema::table('repositories', function($table)
        {
            $table->dropColumn('secret_key');
        });
    }
}
