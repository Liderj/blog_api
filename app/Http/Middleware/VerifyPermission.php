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
        $allPermission = Permission::where('status', 1)->pluck('url');
        $url  = $request->route()->getName();
        $permissions = Role::find($this->auth->user()->roles)->permission()->where('status', 1)->pluck('url');
        if($allPermission->contains($url) &&!$permissions->contains($url)){
          throw new UnauthorizedHttpException('jwt-auth', '你没有此权限',null,403);
        };
        return $next($request);
    }
}
