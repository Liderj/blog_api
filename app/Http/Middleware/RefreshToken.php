<?php

namespace App\Http\Middleware;

use App\MyTrait\ApiMessage;
use App\Role;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class RefreshToken extends BaseMiddleware
{
  use ApiMessage;
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Closure $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $this->checkForToken($request);
    try {
      if ($this->auth->parseToken()->authenticate()) {
        $url  = $request->route()->getName();
        $permissions = Role::find($this->auth->user()->roles)->permission()->where('status', 1)->pluck('url');
        $response = $next($request);
        if(!$permissions->contains($url)){
          throw new UnauthorizedHttpException('jwt-auth', '你没有此权限',null,403);
        };
        return $response;
      }
      throw new UnauthorizedHttpException('jwt-auth', '未登录');
    } catch (TokenExpiredException $exception) {
      throw new UnauthorizedHttpException('jwt-auth', '登录已过期，请重新登录');
    }
    return $next($request);
  }

  public function checkForToken( Request $request)
  {
    if (!$this->auth->parser()->setRequest($request)->hasToken()) {
      throw new UnauthorizedHttpException('jwt-auth', '请求header中未添加token');
    }
  }

}
