<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{

  public function info()
  {
    $user = Auth::user();
    $user->roles = $this->getRoles($user->roles);
    return $this->success($user);
  }

  public function getRoles($id)
  {
    $roles = Role::find($id);
    $permission_list = $roles->permission()->where('status', 1)->get();
    $roles['permission_list'] = $permission_list->isEmpty() ? null : $this->format($permission_list->toArray());
    return $roles;
  }


}
