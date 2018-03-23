<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('rid')->comment('角色id');
            $table->bigInteger('pid')->comment('父权限id');
            $table->integer('type')->comment('权限类别 O:菜单权限，1:接口权限');
            $table->integer('status')->default('1')->comment('权限状态 O:关闭，1:开启');
            $table->string('name',255)->comment('权限名称');
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
        Schema::dropIfExists('permission');
    }
}
