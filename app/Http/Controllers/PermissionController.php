<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class PermissionController extends BaseController
{
  public function index( Request $request)
  {
    //  查询所有权限
    $page_size = $request->query('page_size', 10);//每页条数
    $page = $request->query('page',1);
    $search = $request->query('search');//搜索
    $type =  $request->query('type');
    $pid = $request->query('pid');
 //    查询结果分页
    $res = Permission::where([
      ['name', 'like', '%' . $search . '%'],
    ])->where(function ($query ) use ($type,$pid){
      if($type !== null){
        $query->where('type',$type);
      }
      if($pid !== null){
        $query->where('pid',$pid);
      }
    })
      ->skip(($page-1)*$page_size)->take($page_size)->get();
//    获取总条数
    $count = Permission::all()->count();
    $data = [
      'count'=>$count,
      'current_page'=>$page,
      'list'=>$res->isNotEmpty()?$res:null
    ];
    return $this->success($data);
  }

  public function store(Request $request)
  {
//      添加新权限
    $rules = [
      'name' => [
        'required',
        'unique:permissions,name'
      ],
      'type' => 'required',
      'pid' => 'required',
      'status' => 'required'
    ];
    $messages = [
      'name.required' => '名称不能为空',
      'name.unique' => '该名称已存在',
    ];
    $this->validate($request, $rules, $messages);
    if($request->input('pid')!=0 &&Permission::where('id',$request->input('pid'))->get()->first()->type!==0){
      return $this->failed('父权限不能为接口权限');
    }

    $role = new Permission($request->only(['name', 'type', 'pid', 'url', 'status']));
    return $role->save() ? $this->message('添加成功') : $this->failed('添加失败');
  }

  public function show(Permission $permission)
  {
//      权限详情
    return $permission ? $this->success($permission) : $this->failed('没有找到此权限');
  }

  public function update(Request $request, Permission $permission)
  {
//更新权限
//    参数验证
    $rules = [
      'name' => 'required',
      'type' => 'required',
      'pid' => 'required',
      'url' => 'required',
      'status' => 'required'
    ];
    $messages = [
      'name.required' => '名称不能为空',
      'url.required' => 'api或菜单不可为空'
    ];
    $this->validate($request, $rules, $messages);

    foreach ($request->all() as $key => $v) {
      if ($v != $permission[$key]) {
        $permission[$key] = $v;
      }
    }
    return $permission->save() ? $this->message('修改成功') : $this->failed('修改失败');
  }

  public function destroy(Request $request, Permission $permission)
  {

    //删除权限需验证登录密码
    if(!$request->input('password') || !Hash::check( $request->input('password'),Auth::user()->password)){
      return $this->failed('密码错误');
    }
    //      创建数据库事务
    DB::beginTransaction();
    try {
      //    删除关联
      $permission->roles()->detach();

      //    删除权限
      $res = $permission->delete();

      DB::commit();
      return $res ? $this->message('权限删除成功') : $this->failed('权限删除失败');
    } catch (\Exception $exception) {
      //      遇到异常回滚事务
      DB::rollback();
      return $this->failed('操作失败，请刷新重试');
    }
  }
}
