<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesPermissionTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('roles_permissions', function (Blueprint $table) {
      $table->increments('id');
      $table->bigInteger('rid')->comment('角色id');
      $table->bigInteger('perid')->comment('权限id');
      $table->timestamps();
    });
    Schema::rename('permission', 'permissions');
    Schema::table('permissions', function (Blueprint $table) {
      $table->dropColumn('rid');
      $table->string('url', 255)->comment('菜单对应路由或api');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('roles_permission');
  }
}
