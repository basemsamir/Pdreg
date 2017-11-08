<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;

class VisitComplaint extends Model
{
    use Logger;
    protected $table = 'visit_complaints';
    protected $fillable = 
	[
		'visit_id',
		'content',
		'typist_id',
	];
	
	public function visit(){
	
		return $this->belongsTo('App\Visit');
	}
}
