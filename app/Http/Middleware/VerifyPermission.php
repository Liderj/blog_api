<?php

namespace App\Http\Middleware;

use App\Permission;
use App\Role;
use Closure;
use Auth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;



class VerifyPermission extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $url  = $request->route()->getName();
//        如果是超管 则通过请求
        if(\Illuminate\Support\Facades\Auth::user()->id == 1){
          return $next($request);
        }
//        获取请求的权限
        $allPermission = Permission::where('status', 1)->pluck('url');
//        获取当前用户所有权限
        $permissions = Role::find($this->auth->user()->roles)->permission()->where('status', 1)->pluck('url');
//     判断是否拥有权限
        if($allPermission->contains($url) &&!$permissions->contains($url)){
          throw new UnauthorizedHttpException('jwt-auth', '你没有此权限',null,403);
        };
        return $next($request);
    }
}
