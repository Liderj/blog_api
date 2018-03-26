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
    $user->roles = $this->getRoles($user->id);
    return $this->format($this->getRoles($user->id)['permission_list']);
//    return $this->success($user);
  }

  public function getRoles($id)
  {
    $roles = Role::find($id);
    $permission_list = $roles->permission()->where('status', 1)->get();
    $roles['permission_list'] = $permission_list->isEmpty() ? null : $permission_list->toArray();

    return $roles;
  }

  public function format($data)
  {
    if (!empty($data)) {
      foreach ($data as $key) {
        if($key['pid']!=0)
        {
          foreach ($data as $a) {
            if($a['id'] == $key['pid'])
            {
              $a['child'] =$key;
              break;
            }
          }
        }
      }
    }
  }
}
