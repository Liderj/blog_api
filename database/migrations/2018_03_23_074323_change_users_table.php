<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
      Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('level');
        $table->bigInteger('roles')->comment('角色id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('users', function (Blueprint $table) {
        $table->integer('level')->default(1)->comment('用户等级：0、超级管理员，1、普通，2、高级管理');
        $table->dropColumn('roles');
      });
    }
}
