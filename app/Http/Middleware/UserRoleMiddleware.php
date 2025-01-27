<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class UserRoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        $current_role = null;
        $user = app('auth')->user();

        if($user){
            if($user->is_admin && in_array('admin', $roles)){
                return $next($request);
            }
            if($user->is_merchant && in_array('merchant', $roles)){
                return $next($request);
            }
        }
        return abort(401, 'You do not have sufficient permissions to access this area');
    }
}
