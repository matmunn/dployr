<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('groups', function ($table) {
            $table->increments('id');
            $table->integer('admin_user');
            $table->integer('plan_id');
            $table->string('group_name');
            $table->timestamps();
        });

        Schema::table('users', function ($table) {
            $table->dropColumn('plan_id');
            $table->integer('group_id');
        });

        Schema::table('repositories', function ($table) {
            $table->renameColumn('user_id', 'group_id');
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
        Schema::drop('groups');

        Schema::table('users', function ($table) {
            $table->integer('plan_id');
            $table->dropColumn('group_id');
        });

        Schema::table('repositories', function ($table) {
            $table->renameColumn('group_id', 'user_id');
        });
    }
}
