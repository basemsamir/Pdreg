<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;

class Patient extends Model
{
    //
	use Logger;
	protected $table = 'patients';
	protected $fillable = [
		'id',
		'serial_number',
        'name',
		'gender',
        'sid',
		'address',
		'birthdate',
		'age',
		'social_status',
		'phone_num',
		'job',
    ];
	public function visits(){
	
		return $this->hasMany('App\Visit');
	}
	public function scopeNumberOfPatientsToday($query){
		
		return $query->whereDate('created_at','=',date('Y-m-d',time()))->get();
		
	}
	
}
