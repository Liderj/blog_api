<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mobile', 11)->comment('手机号码');
            $table->string('nickname',16)->comment('昵称');
            $table->string('password',255)->comment('登录密码');
            $table->integer('sex')->default(1)->comment('性别：1、男，2、女，3、保密');
            $table->string('avatar')->comment('头像')->nullable($value = true);
            $table->integer('status')->default(0)->comment('状态：1、正常，0、锁定');
            $table->integer('type')->default(0)->comment('用户类型：1、普通用户，0、管理用户');
            $table->integer('level')->default(1)->comment('用户等级：0、超级管理员，1、普通，2、高级管理');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
