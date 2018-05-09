<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\BaseController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{

//    前台登录
  public function login(Request $request)
  {
    // 验证规则，使用手机号码登录
    $rules = [
      'mobile' => [
        'required',
      ],
      'password' => 'required|string|min:6|max:18',
    ];
    $messages = [
      'required' => '手机号或密码不能为空',
    ];
    // 验证参数，如果验证失败，则会抛出 ValidationException 的异常
    $params = $this->validate($request, $rules, $messages);
//    判断输入密码与数据库加密密码是否一致
    if ($token = Auth::guard('api')->attempt($params)) {
//        判断用户是否被锁定
      return  Auth::user()->status ? $this->success(compact('token')) : $this->failed('该账户已被锁定，请联系相关人员解锁');
    }

    return $this->failed('账号或密码错误');
  }

  public function register(Request $request)
  {
//      注册
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
      'password.required' => '密码不能为空',
    ];
    $this->validate($request, $rules, $messages);
    $user = new User($request->only(['mobile', 'password', 'nickname', 'avatar', 'sex']));
    $user->status = 1;
    $user->type = 1;
    $user->roles = 13;
    $user->password = bcrypt($user->password);
//    数据库插入数据
    return $user->save() ? $this->message('注册成功') : $this->failed('注册失败，请刷新后重试');
  }

  public function logout()
  {
//      退出登录
    Auth::guard('api')->logout();

    return $this->message('退出成功');
  }
  public function info()
  {
//      获取当前登录用户详情
    $user = Auth::user();
    if($user->status == 0){
      return $this->failed('您已被锁定，请联系管理员解锁账号');
    }else{
      return $this->success($user);
     }
  }

  public function changePWd(Request $request)
  {
//      修改密码
    $rules = [
      'oldPassword' => 'required',
      'password' => 'required',
    ];
    $messages = [
      'oldPassword.required' => '原密码不能为空',
      'password.required' => '新密码不能为空',
    ];
    $this->validate($request, $rules, $messages);
    $user=Auth::user();//获取当前登录用户
    if(!Hash::check( $request->input('oldPassword'),$user->password)){
      return $this->failed('原密码错误');
    }
    $user->password = bcrypt($request->input('password'));//bcrypt  加密密码
    return $user->save() ? $this->message('修改成功') : $this->failed('修改失败，请刷新后重试');
  }
  public function changeInfo(Request $request)
  {
//      修改用户资料
    $rules = [
      'avatar' => 'required',
      'nickname' =>['unique:users,nickname','required'],
      'sex'=>'required'
    ];
    $messages = [
      'avatar.required' => '头像不能为空',
      'sex.required' => '性别不能为空',
      'nickname.required'=>'昵称不能为空',
      'nickname.unique'=>'此昵称已存在'
    ];
    $this->validate($request, $rules, $messages);
    $user=Auth::user();
    $user->avatar = $request->input('avatar');
    $user->nickname = $request->input('nickname');
    $user->sex = $request->input('sex');
    $user->password = bcrypt($request->input('password'));
    return $user->save() ? $this->message('修改成功') : $this->failed('修改失败，请刷新后重试');
  }
}
