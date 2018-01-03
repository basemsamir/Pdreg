<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;
class Entrypoint extends Model
{
	use Logger;
    protected $table = 'entrypoints';
	protected $fillable = [
        'name',
		'type',
		'sub_type',
		'location',
		];
	public function users(){
	
		return $this->belongsToMany('App\User');
	}
	public function visits(){
		
		return $this->hasMany('App\Visit');
	}
	public function data_entry_place_type(){
		return $this->belongsTo('App\DataEntryPlaceType','type');
	}
}
