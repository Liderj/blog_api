<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends BaseController
{

  public function index(Request $request)
  {
    return $this->success($this->paginate('user',10,1,'1358'));
    $page_size = $request->query('page_size',10);
    return $this->success(User::paginate($page_size));
  }
}
