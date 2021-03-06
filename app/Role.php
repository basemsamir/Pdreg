<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;
class Role extends Model
{
    //
	use Logger;
	protected $fillable=['name'];
	
	public function users(){
	
		return $this->hasMany('App\User');
	}
}
