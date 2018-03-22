<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
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
        $result = Auth::user()->all()[0];
        $result['token'] = $token;
        return response( ['code'=>200,'data'=>$result]);
      }
      return response(['error' => '账号或密码错误','code'=>400]);
    }
    public function logout()
    {
      Auth::guard('api')->logout();

      return response(['message' => '退出成功']);
    }
}
