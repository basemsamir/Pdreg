<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;

class VisitMedicine extends Model
{
    use Logger;
    protected $table = 'visit_medicines';
    protected $fillable = 
	[
		'visit_id',
		'name',
		'accessories',
		'typist_id',
	];
	
	public function visit(){
	
		return $this->belongsTo('App\Visit');
	}
}
