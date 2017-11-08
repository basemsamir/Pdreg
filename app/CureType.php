<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;

class CureType extends Model
{
    //
	use Logger;
	public function visits(){
		return $this->hasMany('App\Visit');
	}
}
