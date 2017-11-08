<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;
class Procedure extends Model
{
    //
	use Logger;
	protected $fillable=['proc_ris_id','type_id','name','device_id'];
	
	public function proceduretype(){
	
		return $this->belongsTo('App\ProcedureType');
	}
	public function device(){
	
		return $this->belongsTo('App\MedicalDevice');
	}
}
