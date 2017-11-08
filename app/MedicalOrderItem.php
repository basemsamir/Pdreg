<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;
class MedicalOrderItem extends Model
{
    //
	use Logger;
	protected $fillable=['visit_id','proc_id','doctor_id'];
	
	public function visit(){
	
		return $this->belongsTo('App\Visit');
	}
}
