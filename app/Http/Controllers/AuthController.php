<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
      // 验证规则，使用手机号码登录
      $rules = [
        'mobile'   => [
          'required',
        ],
        'password' => 'required|string|min:6|max:18',
      ];
      $messages = [
        'required'=>'手机号或密码不能为空',
      ];
      // 验证参数，如果验证失败，则会抛出 ValidationException 的异常
        $params = $this->validate($request, $rules,$messages);

      if($token = Auth::guard('api')->attempt($params))
      {
        return !Auth::user()->status ? $this->success(compact('token')):$this->failed('该账户已被锁定，请联系相关人员解锁');
      }
      return  $this->failed('账号或密码错误');
    }
    public function logout()
    {
      Auth::guard('api')->logout();

      return $this->message('退出成功');
    }
}
