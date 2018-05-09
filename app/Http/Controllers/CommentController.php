<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class CommentController extends BaseController
{
  public function index(Request $request)
  {
//      获取所有评论
    $page_size = $request->query('page_size', 10);//每页条数
    $page = $request->query('page',1);
    $search = $request->query('search');//评论搜索
    $res = Comment::where([
      ['content', 'like', '%' . $search . '%'],
    ])
      ->latest()
      ->skip(($page - 1) * $page_size)->take($page_size)->get();//    查询结果分页
//    获取总条数
    $count = Comment::where([
      ['content', 'like', '%' . $search . '%'],
    ])->count();
//    分页结果添加评论id 和文章id
    foreach ($res as $key=>$val){
      $val['user']= $val->user()->get(['id', 'nickname'])->first();
      $val['post']= $val->post()->get(['id', 'title'])->first();
    }
    $data = [
      'count'=>$count,
      'current_page'=>$page,
      'list'=>$res
    ];
    return $this->success($data);
  }

  public function show(Comment $comment)
  {
//      获取评论详情
      $comment->user = $comment->user()->get(['id', 'nickname','avatar'])->first();
      return $this->success($comment);
    }

  public function destroy(Comment $comment)
  {
//      删除评论及以下的回复

//      创建数据库事务
    DB::beginTransaction();
    $post = $comment->post()->get()->first();
    $count = 0;
    try {
      //    删除所有回复
        foreach ($comment->reply as $item){
          $count ++;
          $item->delete();
        }
      $res =  $comment->delete();
      $post->comment_count= $post->comment_count - 1-$count;
      $post->save();
      DB::commit();
      return $res ? $this->message('删除成功') : $this->failed('删除失败');
    } catch (\Exception $exception) {
      //      遇到异常回滚事务
      DB::rollback();
      return $this->failed('操作失败，请刷新重试');
    }
    }
}
