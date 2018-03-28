<?php

namespace App\Http\Middleware;

use App\Permission;
use App\Role;
use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyPermission
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
      dd($request->r);
      dd();
//       dd( Role::find(Auth::user()->id)->permission()->where('status', 1)->get());
        return $next($request);
    }
}
