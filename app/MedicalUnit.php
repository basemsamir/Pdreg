<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;
class MedicalUnit extends Model
{
    //
	use Logger;
	protected $table = 'medical_units';
	protected $fillable = [
     	'name',
		'type'
	];
	public function visits(){
	
		return $this->belongsToMany('App\Visit')->withPivot('convert_to', 'department_conversion')->withTimestamps();
	}
	public function users(){
	
		return $this->belongsToMany('App\User')->withTimestamps();
	}
}
