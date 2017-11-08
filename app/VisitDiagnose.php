<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;
class VisitDiagnose extends Model
{
	use Logger;
    protected $table = 'visit_diagnoses';
    protected $fillable = 
	[
		'visit_id',
		'content',
		'content_in_english',
		'cure_description',
		'accessories',
		'typist_id',
	];
	
	public function visit(){
	
		return $this->belongsTo('App\Visit');
	}
	
	
}
