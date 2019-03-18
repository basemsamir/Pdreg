<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Http\Requests;
use App\User;
use App\Visit;
use App\Patient;
use App\Entrypoint;
use Auth;
use Session;
/**
 * @resource
 * FrontController
   It is the main controller that is responsible for redirecting the user to his module
   after the authorization operation is successed.
 */
class FrontController extends Controller
{
    /**
	 * Index function
	 * Route: '/' <br>
	 * Usage: Get user role
			  Store user role in session to keep it on all pages for controlling the module view
			  according to each user role, we store some important data in sessions to keep them on all pages of user`s module.
			  Redirect to user module controller
	 *
	 *
	*/
	public function index(Request $request){
	
		$rule_lang="auth.failed_user_association";
		$user=User::find(Auth::id());
		$role_name=$user->role->name;

      if($role_name == "Doctor" || $role_name == "Nursing"){
        $medicalunits=$user->medicalunits()->orderBy('type','asc')->get();
		if(!$this->checkUserAssiocation($user))
		{
			Session::flush();
			Auth::guard(property_exists($this, 'guard') ? $this->guard : null)->logout();
			return redirect()->back()->withErrors([
				property_exists($this, 'username') ? $this->username : 'email'=>Lang::get($rule_lang)]);
		}
        $mid=$medicalunits[0]->id;
        return redirect()->action('VisitController@index',array('mid'=>$mid,'desk'=>-1));
			}
			elseif($role_name == "Pharmacist"){
				return redirect('pharm/patient_drugs');
			}
			elseif($role_name == "Radiology Tech"){
				return redirect('rad/patient_rads');
			}
      elseif($role_name=="Admin" || $role_name=="SubAdmin"){

          return redirect()->action('AdminController@index');
      }
      else {
		  if(!$this->checkUserAssiocation($user))
		  {
			Session::flush();
			Auth::guard(property_exists($this, 'guard') ? $this->guard : null)->logout();
			return redirect()->back()->withErrors([
				property_exists($this, 'username') ? $this->username : 'email'=>Lang::get($rule_lang)]);
		  }
          if($role_name=="Entrypoint"){
			  if($user->entrypoints()->first()->sub_type == "exit_only")
				  return redirect()->action('PatientController@showinpatientvisits');
			return redirect()->action('PatientController@index');
			
		  }
		  elseif($role_name=="Desk"){
			return redirect()->action('PatientController@indexDesk',array('pid'=>-1));
			
		  }
          else{
            return redirect()->action('PatientController@indexTicket',array('pid'=>-1));
          }
	  }
	}
	public function no_javascript(){
		return view("no_javascript");
	}
	
	
	/**
	 * Check if the logged user (doctor, receiptionist, desk or entrypoint) has (medicalunit or entrypoint)
	 
	**/
	private function checkUserAssiocation($user)
	{
		$role_name=$user->role->name;
		switch($role_name){
			case 'Doctor':
				if($user->medicalunits()->count() > 0)
					return true;
				return false;
			break;
			case 'Receiption':
			case 'Desk':
			case 'Entrypoint':
				if($user->entrypoints()->count() > 0)
					return true;
				return false;
			case 'Pharmacist':
				return false;
			break;
		}
	}
}
