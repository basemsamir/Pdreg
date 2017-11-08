<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;

class DataEntryPlaceType extends Model
{
    //
	use Logger;
	public function entrypoints(){
		return $this->hasMany('App\Entrypoint');
	}
}
