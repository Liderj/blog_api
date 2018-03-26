<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;

class PermissionController extends BaseController
{
  public function all()
  {
    return $this->success($this->format(Permission::all()->toArray()));
  }

  public function getOne($id)
  {
    $res = Permission::where('id', $id)->first();
    return $res ? $this->success($res) : $this->failed('没有找到此权限');
  }

  public function delete($id)
  {
    $permission = Permission::find([['id',$id],['pid',$id]]);
    $res =Permission::destroy($id);
    return $res ? $this->message('删除成功') : $this->failed('删除失败');
  }
}
