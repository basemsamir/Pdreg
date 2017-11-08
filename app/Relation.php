<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;
class Relation extends Model
{
	use Logger;
    protected $table = 'relations';
	protected $fillable = [
        'name',
		];
	public function visits(){
	
		return $this->hasMany('App\Visit');
	}
}
