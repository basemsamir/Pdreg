<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;
class Wsconfig extends Model
{
    //
	use Logger;
	protected $table="wsconfig";
	protected $fillable = 
	[
		'url',
		'sending_app',
		'sending_fac',
		'receiving_app',
		'receiving_fac',
	];
}
