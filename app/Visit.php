<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;
use Carbon\Carbon;

class Visit extends Model
{
	use Logger;
    protected $table = 'visits';
		protected $fillable = [
			'patient_id',
			'serial_number',
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
			'entry_date',
			'registration_datetime',
			'watching_status',
			'ticket_status',
			'sent_by_person',
			'ticket_companion_name',
			'ticket_companion_sin'
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
	/* Get number of outpatients were reserved from clinic reservation office */
	public function scopeNumberOfOutpatientsFromClinic($query){
		return $query->where('cancelled',false)
					 ->whereDate('created_at','=',Carbon::today()->format('Y-m-d'))
					 ->whereNull('ticket_type')
					 ->where('ticket_number','!=','')
					 ->get();
	}
	/* Get number of outpatients were reserved from desk reservation office */
	public function scopeNumberOfOutpatientsFromDesk($query){
		return $query->where('cancelled',false)
					 ->whereDate('created_at','=',Carbon::today()->format('Y-m-d'))
					 ->where(function($query){
						$query->whereNotNull('ticket_type')
					 		  ->orWhereNotNull('convert_to_entry_id');
					})->get();
					 
	}
	public function entrypoint(){
		
		return $this->belongsTo('App\Entrypoint','entry_id');
	}
	public function user(){
		
		return $this->belongsTo('App\User');
	}
	public function medicalunits(){

		return $this->belongsToMany('App\MedicalUnit')->withPivot('convert_to', 'department_conversion')->withTimestamps();
	}
	public function orders(){

		return $this->hasMany('App\MedicalOrderItem');
	}
	public function cure_type(){
		return $this->belongsTo('App\CureType');
	}
}
