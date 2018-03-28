<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends BaseController
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $page_size = $request->query('page_size', 10);//每页条数
    $search = $request->query('search' );//文章标题关键字搜索
    $status = $request->query('status' );//文章状态
    $cid = $request->query('type');//文章类型
//    查询结果分页
    $res = Post::where([
      ['title', 'like', '%' . $search . '%'],
    ])->where(
      function ($query)use ($status,$cid) {
        if($status !==null){
          $query->where('status', $status);
        }
        if($cid !==null){
          $query->where('cid', $cid);
        }
      }
    )
      ->latest()
      ->paginate($page_size);
    return $this->success($res);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Post $post)
  {

  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Post $post
   * @return \Illuminate\Http\Response
   */
  public function show(Post $post)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Post $post
   * @return \Illuminate\Http\Response
   */
  public function edit(Post $post)
  {
    //
  }

}
