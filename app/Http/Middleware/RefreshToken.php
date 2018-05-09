<?php

namespace App\Http\Middleware;

use App\MyTrait\ApiMessage;
use App\Permission;
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
        return $next($request);
      }
      throw new UnauthorizedHttpException('jwt-auth', '未登录');
    } catch (TokenExpiredException $exception) {
      throw new UnauthorizedHttpException('jwt-auth', '登录已过期，请重新登录');
    }
    return $next($request);
  }

  public function checkForToken( Request $request)
  {
//      检验token合法性
    if (!$this->auth->parser()->setRequest($request)->hasToken()) {
      throw new UnauthorizedHttpException('jwt-auth', '请求header中未添加token');
    }
  }

}
