<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends BaseController
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return $this->success(Role::all());
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request)
  {
    $rules = [
      'name' => [
        'required',
        'unique:roles,name'
      ],
      'status' => 'required',
    ];
    $messages = [
      'name.required' => '名称不能为空',
      'name.unique' => '该名称已存在'
    ];
    $this->validate($request, $rules, $messages);
    $role = new Role($request->only(['name', 'status']));
    return $role->save() ? $this->message('添加成功') : $this->failed('添加失败');
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Roles $roles
   * @return \Illuminate\Http\Response
   */
  public function show(Role $role)
  {
    $permission_list = $role->permission()->get();
    $role['permission_list'] = $permission_list->isEmpty() ? null : $this->format($permission_list->toArray());
    return $this->success($role);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \App\Roles $roles
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Role $role)
  {
    $rules = [
      'name' => [
        'required',
        'unique:roles,name'
      ],
      'status' => 'required',
    ];
    $messages = [
      'name.required' => '名称不能为空',
      'name.unique' => '该名称已存在'
    ];
    $this->validate($request, $rules, $messages);
    $role->name = $request->input('name');
    $role->status = $request->input('status');
    return $role->save() ? $this->message('修改成功') : $this->failed('修改失败');

  }

  public function updatePermission(Request $request, Role $role)
  {
    $res = $role->permission()->sync(json_decode($request->permissionId));
    return $res ? $this->message('权限修改成功') : $this->failed('权限修改失败');

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Roles $roles
   * @return \Illuminate\Http\Response
   */
  public function destroy(Role $role)
  {
    //创建数据库事务
    DB::beginTransaction();
    try {
      //    将拥有该角色的管理员全部分配到未设置权限的角色
      User::where('roles', $role->id)->update(['roles' => 2]);
      //    删除关联
      $role->permission()->detach();
      //    删除角色
      $res = $role->delete();

      DB::commit();
      return $res ? $this->message('角色删除成功') : $this->failed('角色删除失败');
    } catch (\Exception $exception) {
      //      遇到异常回滚事务
      　DB::rollback();
      return $this->failed('操作失败，请刷新重试');
    }
  }
}
