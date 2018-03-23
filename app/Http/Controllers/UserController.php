<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    //

  public function info()
  {
    return $this->success(Auth::user());
  }
}
