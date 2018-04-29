<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\BaseController;
use App\Post;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    //
  public function index(Request $request)
  {
    $page_size = $request->query('page_size', 10);//每页条数
    $search = $request->query('search');//文章标题关键字搜索
    $cid = $request->query('type');//文章类型
    $page = $request->query('page', 1);
    $hot = $request->query('hot');

//    查询结果分页
    $res = Post::where([
      ['title', 'like', '%' . $search . '%'],
      ['status','=',1]
    ]);
    if ($cid !== null) {
      $res = $res->where('cid', $cid);
    };
    if ($hot !== null) {
      $res = $res->where('is_hot', $hot);
    };
//    获取总条数
    $count = $res->count();
    $list = $res->skip(($page - 1) * $page_size)->take($page_size)->get(['id','cid','img','likes','title','pid','is_comment','created_at','comment_count']);
    foreach ($list as $key => $value){
      $value['user'] = $value->user()->get(['nickname','avatar'])->first();
      $value['category'] = $value->category()->first()->name;
    }
    $data = [
      'count' => $count,
      'current_page' => $page,
      'list' => $list->isNotEmpty() ? $list : null
    ];
    return $this->success($data);
  }

  public function show(Post $post)
  {
    $post->author = $post->user()->get(['id', 'nickname','avatar'])->first();
    $post->category = $post->category()->get(['name'])->first()->name;
    return $this->success($post);
  }

  public function like(Post $post)
  {
    $post->likes = $post->likes+1;
    return $post->save() ? $this->message('点赞成功') : $this->failed('点赞失败');

  }
}
