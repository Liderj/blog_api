<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends BaseController
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
//      获取所有文章列表
    $page_size = $request->query('page_size', 10);//每页条数
    $search = $request->query('search');//文章标题关键字搜索
    $status = $request->query('status');//文章状态
    $cid = $request->query('type');//文章分类
    $page = $request->query('page', 1);//当前页数
    $hot = $request->query('hot');//热门推荐

//    查询结果分页
    $res = Post::where([
      ['title', 'like', '%' . $search . '%'],
    ]);
    if ($status !== null) {
      $res = $res->where('status', $status);
    };
    if ($cid !== null) {
      $res = $res->where('cid', $cid);
    };
    if ($hot !== null) {
      $res = $res->where('is_hot', $hot);
    };
//    获取总条数
    $count = $res->count();
    $list = $res->skip(($page - 1) * $page_size)->take($page_size)->get(['id','pid','cid','likes','title','status','is_hot','is_comment','created_at']);
    foreach ($list as $key => $value){
      $value['user'] = $value->user()->first()->nickname;
      $value['category'] = $value->category()->first()->name;

    }
    $data = [
      'count' => $count,
      'current_page' => $page,
      'list' => $list->isNotEmpty() ? $list : null
    ];
    return $this->success($data);
  }


  /**
   * Display the specified resource.
   *
   * @param  \App\Post $post
   * @return \Illuminate\Http\Response
   */
  public function show(Post $post)
  {
//      文章详情
    $post->author = $post->user()->get(['id', 'nickname','avatar'])->first();
    $post->category = $post->category()->get(['name'])->first()->name;
    return $this->success($post);
  }


  public function destroy(Post $post)
  {
//      删除文章
    DB::beginTransaction();
    try {
//        评论和回复删除
      $post->comments()->delete();
      $res= $post->delete();
      DB::commit();
      return $res ? $this->message('文章删除成功') : $this->failed('文章删除失败');
    } catch (\Exception $exception) {
      //      遇到异常回滚事务
      　DB::rollback();
      return $this->failed('操作失败，请刷新重试');
    }
  }

//  关闭文章
  public function disable(Post $post)
  {
    if ($post->status == 0) {
      $post->status = 1;
    } else {
//      关闭后不允许评论
      $post->status = 0;
      $post->is_comment = 0;
    }
    return $post->save() ? $this->message('修改状态成功') : $this->failed('修改状态失败');
  }

  public function setComment(Post $post)
  {
//    修改评论状态
    $post->is_comment == 0 ? $post->is_comment = 1 : $post->is_comment = 0;
    return $post->save() ? $this->message('已更改评论状态') : $this->failed('修改状态失败');
  }

//  设置热推
  public function setHot(Post $post)
  {
    if($post->cid == 1){
      $this->failed('微博不能设置为热推文章');
    }
    $post->is_hot == 0 ? $post->is_hot = 1 : $post->is_hot = 0;
    return $post->save() ? $this->message('已更改热推状态') : $this->failed('修改状态失败');
  }

//  获取热推列表
  public function top()
  {
//    点赞排行前10的文章
    $top_10 = Post::where('status', 1)->where('cid','<>',1)->orderBy('likes', 'desc')->get()->take(10);
//    已设置推荐的文章
    $hot = Post::where([['status', 1], ['is_hot', 1],['cid','<>',1]])->get();
//    合并去重
    foreach ($hot as $key => $value) {
      if (!$top_10->contains($value)) {
        $top_10->prepend($value);
      }
    }
    foreach ($top_10 as $key => $value) {
      $value['user'] = $value->user()->first()->nickname;
      $value['category'] = $value->category()->first()->id;
    }
    return $this->success(array_slice($top_10->toArray(), 0, 10));
  }
}
