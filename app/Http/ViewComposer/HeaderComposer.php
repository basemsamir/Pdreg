<?php

namespace App\Http\ViewComposer;

use Illuminate\View\View;
use Auth;
use App\User;
use App\Entrypoint;

class HeaderComposer
{
    private $entrypoints = [];
	private $medicalunits = [];
    private $user = [];
    private $role_name = [];
	private $entrypoint_sub_type="";
    
    /**
     * Create a movie composer.
     *
     * @return void
     */
    public function __construct()
    {
		$this->user=User::find(Auth::id());
		$this->role_name=$this->user->role->name;
		if($this->role_name=="Admin" || $this->role_name=="SubAdmin")
			$this->entrypoints=Entrypoint::whereIn('type',[1,3])->get();
		elseif($this->role_name=="Receiption" || $this->role_name=="Entrypoint" || $this->role_name=="Desk"){
			$this->entrypoints=$this->user->entrypoints()->get();
			$this->entrypoint_sub_type=$this->user->entrypoints()->first()->sub_type;
		}
		elseif($this->role_name=="Doctor" || $this->role_name=="Nursing")
			$this->medicalunits=$this->user->medicalunits()->orderBy('type','asc')->get();
       
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
		
       $view->with('role_name',$this->role_name)
			->with('entrypoints_header',$this->entrypoints)
			->with('entrypoint_sub_type',$this->entrypoint_sub_type)
			->with('medicalunits',$this->medicalunits);
    }
}