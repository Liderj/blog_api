<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('posts', function (Blueprint $table) {
      $table->increments('id');
      $table->bigInteger('pid')->comment('归属id');
      $table->bigInteger('cid')->comment('分类id');
      $table->integer('likes')->default(0)->comment('点赞次数');
      $table->string('title', 255)->comment('标题')->nullable();
      $table->integer('status')->default(1)->comment('文章状态:1.已发布，2未发布，');
      $table->longText('content')->comment('内容');
      $table->integer('is_hot')->default(0)->comment('热推:0:否，1：是');
      $table->integer('is_comment')->default(1)->comment('是否允许评论:0:否，1：是');
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
    Schema::dropIfExists('posts');
  }
}
