<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class CheckIfUserIsReceiption
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
		$user=User::find(Auth::id());
		if($user->role->name != "Receiption"){
			 return redirect()->guest('auth/login');
		}
        return $next($request);
    }
}
