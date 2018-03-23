<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('comment', function (Blueprint $table) {
      $table->increments('id');
      $table->bigInteger('pid')->comment('文章id');
      $table->bigInteger('uid')->comment('评论者id');
      $table->string('content', 500)->comment('评论内容');
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
    Schema::dropIfExists('comment');
  }
}
