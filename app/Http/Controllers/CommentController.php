<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
  public function index()
  {
    
    }

  public function show(Comment $comment)
  {
      return $this->success($comment->post());
    }
}
