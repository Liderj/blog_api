<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
//   所有用户
  public function index(Request $request)
  {
    $page_size = $request->query('page_size', 10);//每页条数
    $search = $request->query('search', '');//关键字搜索
    $status = $request->query('status', 1);//用户状态
    $type = $request->query('type', 1);//用户类型
//    查询结果分页
    $res = User::where([
      ['nickname', 'like', '%' . $search . '%'],
      ['status', '=', $status],
      ['type', '=', $type]
    ])
      ->orWhere(
        [
          ['mobile', 'like', '%' . $search . '%'],
          ['status', '=', $status],
          ['type', '=', $type]
        ]
      )
      ->orderBy('created_at', 'desc')
      ->paginate($page_size);
    return $this->success($res);
  }

//  用户详情
  public function show(User $user)
  {
    return $this->success($user);
  }

//  更新管理员信息
  public function update(Request $request, User $user)
  {

    $rules = [
      'password' => 'required',
      'nickname' => 'required',
    ];
    $messages = [
      'nickname.required' => '昵称不能为空',
      'password.required' => '密码不能为空'
    ];
    $this->validate($request, $rules, $messages);

    if (Auth::user()->type != 1) {
//      要更改的为管理员用户资料时仅超管可操作
      if ($user->type == 0 && Auth::user()->id != 1) {
        return $this->failed('仅超级管理员可修改管理员信息');
      } else {
        $params = $request->only(['password', 'nickname', 'avatar', 'sex', 'status', 'roles', 'type']);
        $params['password'] = bcrypt($params['password']);
        foreach ($params as $key => $v) {
          if ($v != $user[$key]) {
            $user[$key] = $v;
          }
        }
        return $user->save() ? $this->message('修改成功') : $this->failed('修改失败');
      }
    } else {
      return $this->failed('仅超级管理员可修改');
    }

  }

//  新建管理员
  public function store(Request $request)
  {

    $rules = [
      'mobile' => [
        'required',
        'unique:users,mobile'
      ],
      'password' => 'required',
      'nickname' => [
        'required',
        'unique:users,nickname'
      ],
    ];
    $messages = [
      'mobile.required' => '手机号不能为空',
      'mobile.unique' => '该手机号已存在',
      'nickname.required' => '昵称不能为空',
      'nickname.unique' => '该昵称已存在',
      'password.required' => '密码不能为空'
    ];
    $this->validate($request, $rules, $messages);
    $user = new User($request->only(['mobile', 'password', 'nickname', 'avatar', 'sex', 'status', 'roles']));
    $user->type = 0;
    $user->password = bcrypt($user->password);
    return $user->save() ? $this->message('添加成功') : $this->failed('添加失败');
  }

//  删除管理员
  public function destroy(User $user)
  {
    if ($user->roles == 3) {
      return $this->failed('超级管理员不可以删除');
    }
    //      创建数据库事务
    DB::beginTransaction();
    try {
      //    删除管理员
      $res = $user->delete();
      DB::commit();
      return $res ? $this->message('删除成功') : $this->failed('删除失败');
    } catch (\Exception $exception) {
      //      遇到异常回滚事务
      DB::rollback();
      return $this->failed('操作失败，请刷新重试');
    }
  }

//  用户状态
  public function frozen(User $user)
  {
    if ($user->type == 0 && Auth::user()->id != 1) {
      return $this->failed('仅超级管理员可修改管理员状态');
    }
    $user->status ==0? $user->status=1:$user->status=0;
    return $user->save() ? $this->message('修改状态成功') : $this->failed('修改状态失败');

  }
}
