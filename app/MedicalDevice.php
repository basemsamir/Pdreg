<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;
class MedicalDevice extends Model
{
     //
	 use Logger;
	protected $fillable=['name','location'];
	
	public function procedures(){
	
		return $this->hasMany('App\Procedure','device_id');
	}
}
