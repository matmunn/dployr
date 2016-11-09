<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupIdToRepositories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('repositories', function ($table) {
            $table->integer('group_id');
        });

        Schema::table('users', function ($table) {
            $table->integer('group_id')->nullable()->change();
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
        Schema::table('repositories', function ($table) {
            $table->dropColumn('group_id');
        });

        Schema::table('users', function ($table) {
            $table->integer('group_id')->nullable(false)->change();
        });
    }
}
