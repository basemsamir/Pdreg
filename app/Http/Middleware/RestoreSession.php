<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\User;
use Auth;
use App\Entrypoint;

class RestoreSession
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
          $role_name=$user->role->name;
          Session::put('role_name', $role_name);
          if ($request->is('admin/*')) {
              if(!Session::has('entrypoints')){
                $entrypoints=Entrypoint::all();
                Session::put('entrypoints', $entrypoints);
              }
              if(!($role_name=="Admin" || $role_name=="SubAdmin")){
                Session::flush();
                Session::forget('url.intented');
                return redirect()->guest('auth/login');
              }
          }
          elseif ($request->is('patients/*')) {
              if(!Session::has('entrypoints')){
                $entrypoints=$user->entrypoints()->get();
                Session::put('entrypoints', $entrypoints);
              }
              if( !($role_name=="Entrypoint" || $role_name=="Receiption") ){
                Session::flush();
                Session::forget('url.intented');
                return redirect()->guest('auth/login');
              }

          }
          elseif ($request->is('visits/*') || $request->is('patients/printhistory/*') ) {
              $medicalunits=$user->medicalunits()->orderBy('type','asc')->get();
              $mid=$medicalunits[0]->id;
              if(!Session::has('medicalunits')){
                    Session::put('medicalunits', $medicalunits);
              }
              if(!($role_name == "Doctor" || $role_name == "Nursing")){
                Session::flush();
                Session::forget('url.intented');
                return redirect()->guest('auth/login');
              }
          }
        return $next($request);
    }
}
