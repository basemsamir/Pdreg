<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;

class Visit extends Model
{
	use Logger;
    protected $table = 'visits';
		protected $fillable = [
			'patient_id',
			'ticket_number',
			'ticket_type',
			'c_name',
			'sid',
			'relation_id',
			'address',
			'job',
			'converted_by_doctor',
			'phone_num',
			'entry_reason_desc',
			'entry_time',
			'room_number',
			'file_number',
			'cure_type_id',
			'exit_status_id',
			'reference_doctor_id',
			'exit_date',
			'entry_id',
			'user_id',
			'convert_to_entry_id',
			'convert_to_user_id',
			'converted_by',
			'entry_date'
		];

	public function patient(){

		return $this->belongsTo('App\Patient');
	}
	public function relation(){

		return $this->belongsTo('App\Relation');
	}
	public function diagnoses(){

		return $this->hasMany('App\VisitDiagnose');
	}
	public function complaints(){

		return $this->hasMany('App\VisitComplaint');
	}
	public function medicines(){

		return $this->hasMany('App\VisitMedicine');
	}
	public function scopeNumberOfVisitsToday($query){

		return $query->whereDate('created_at','=',date('Y-m-d',time()))
					 ->where('cancelled',false)
					 ->where('ticket_number','!=',0)->get();

	}
	public function scopeNumberOfInpatients($query){

		return $query->where('cancelled',false)
					 ->where('closed',false)
					 ->whereNotNull('entry_date')->count();

	}
	public function medicalunits(){

		return $this->belongsToMany('App\MedicalUnit')->withTimestamps();
	}
	public function orders(){

		return $this->hasMany('App\MedicalOrderItem');
	}
	public function cure_type(){
		return $this->belongsTo('App\CureType');
	}
}
