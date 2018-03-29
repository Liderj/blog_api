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
    $page_size = $request->query('page_size', 10);//每页条数
    $page = $request->query('page',1);
    $search = $request->query('search');//评论搜索
//    查询结果分页
    $res = Comment::where([
      ['content', 'like', '%' . $search . '%'],
    ])
      ->latest()
      ->skip($page-1)->take($page_size)->get();
//    获取总条数
    $count = Comment::all()->count();
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
      $comment->user = $comment->user()->get(['id', 'nickname','avatar'])->first();
      return $this->success($comment);
    }

  public function destroy(Comment $comment)
  {
//      创建数据库事务
    DB::beginTransaction();
    try {
      //    删除所有回复
        foreach ($comment->reply as $item){
          $item->delete();
        }
      $res =  $comment->delete();
      DB::commit();
      return $res ? $this->message('删除成功') : $this->failed('删除失败');
    } catch (\Exception $exception) {
      //      遇到异常回滚事务
      DB::rollback();
      return $this->failed('操作失败，请刷新重试');
    }
    }
}
