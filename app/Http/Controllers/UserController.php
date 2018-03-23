<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    //

  public function info()
  {
    $user = Auth::user();
    $user->roles = $this->roles($user->id);
    return $this->success($user);
  }

  public function roles($id)
  {
    return Role::where('id',$id)->first();
  }
}
