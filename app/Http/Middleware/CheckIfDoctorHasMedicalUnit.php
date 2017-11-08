<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;


class CheckIfDoctorHasMedicalUnit
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
		//dd(true);
		if($user->role->name == "Doctor"){
			$medicalunits=$user->medicalunits()->count();
			//dd($medicalunits);
			if($medicalunits == 0){
				//Auth::guard($this->getGuard())->logout();
				return redirect()->action('Auth\AuthController@getLogout');
			}
		}
        return $next($request);
    }
}
