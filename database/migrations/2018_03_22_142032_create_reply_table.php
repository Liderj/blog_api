<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReplyTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('reply', function (Blueprint $table) {
      $table->increments('id');
      $table->bigInteger('cid')->comment('评论id');
      $table->bigInteger('f_uid')->comment('回复人id');
      $table->bigInteger('t_uid')->comment('回复人对象id');
      $table->string('content', 500)->comment('回复内容');
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
    Schema::dropIfExists('reply');
  }
}
