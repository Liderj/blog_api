<?php

namespace App\Http\Controllers\frontEnd;

use App\Comment;
use App\Http\Controllers\BaseController;
use App\Keyword;
use App\Post;
use App\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends BaseController
{
  public function index(Request $request)
  {
    $post_id = $request->input('pid');

    $comment =Comment::where('pid', $post_id)->orderBy('created_at', 'desc')->get();
    foreach ($comment as $key=>$val){
      $val['nickname']= $val->user()->get()->first()->nickname;
      $val['child']= $val->reply()->orderBy('created_at', 'desc')->get(['id','f_uid', 'content']);
      foreach ($val['child'] as $key1=>$val1){
        $val1['nickname']= $val1->user()->get()->first()->nickname;
      }
    }
    return $this->success($comment);

  }

  public function addComment(Request $request)
  {
    $rules = [
      'pid'=>'required',
      'content' => 'required'
    ];
    $messages = [
      'content.required' => '内容不能为空',
    ];
    $this->validate($request, $rules, $messages);

    $keywords = Keyword::all();
    foreach ($keywords as $val){
      if(strstr($val->content,$request->input('content'))){
        return $this->failed('您的输入包含敏感字，请重新输入');
      }
    }
    $comment = new Comment($request->only(['pid', 'content']));
    $comment->uid = Auth::id();
    $post = Post::where('id',$request->input('pid'))->get()->first();
    $post->comment_count = $post->comment_count+1;
    $post->save();
    return $comment->save() ? $this->message('评论成功') : $this->failed('评论失败');
  }

  public function addReply(Request $request)
  {
    $rules = [
      'cid'=>'required',
      'content' => 'required'
    ];
    $messages = [
      'content.required' => '内容不能为空',
    ];
    $this->validate($request, $rules, $messages);

    $keywords = Keyword::all();
    foreach ($keywords as $val){
      if(strstr($val->content,$request->input('content'))){
        return $this->failed('您的输入包含敏感字，请重新输入');
      }
    }
    $comment = new Reply($request->only(['cid', 'content']));
    $comment->f_uid = Auth::id();
    $post = Comment::where('id',$request->input('cid'))->get()->first()->post;
    $post->comment_count = $post->comment_count+1;
    $post->save();
    return $comment->save() ? $this->message('回复成功') : $this->failed('回复失败');
  }
}
