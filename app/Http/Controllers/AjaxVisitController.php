<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\VisitDiagnose;
use App\VisitComplaint;
use App\MedicalDevice;
use App\VisitMedicine;
use App\MedicalOrderItem;
use App\Visit;
use DB;
use App\MedicalUnit;

class AjaxVisitController extends Controller
{
    //
	public function checkVisitDiagnoses(Request $request){
		if($request->ajax()){
			$diagnoses= VisitDiagnose::join('users','users.id','=','typist_id')
									 ->select('visit_diagnoses.id','content','users.name','content_in_english','cure_description','accessories',DB::raw('date(visit_diagnoses.created_at) as created_at'))
									 ->where(array('visit_id'=>$request->get('visit_id')))->get();
			if(count($diagnoses) > 0)
				return response()->json(['success' => 'true','data'=>$diagnoses]);
			else
				return response()->json(['success' => 'false']);
		}
		else{
			return abort(404);
		}
	}
	public function checkVisitComplaints(Request $request){
		if($request->ajax()){
			$complaints= VisitComplaint::join('users','users.id','=','typist_id')
									 ->select('content','users.name',DB::raw('date(visit_complaints.created_at) as created_at'))
									 ->where(array('visit_id'=>$request->get('visit_id')))->get();
			if(count($complaints) > 0)
				return response()->json(['success' => 'true','data'=>$complaints]);
			else
				return response()->json(['success' => 'false']);
		}
		else{
			return abort(404);
		}
	}
	public function ajaxProcedures(Request $request){
		if($request->ajax()){
			$device= MedicalDevice::find($request->get('device_id'));
			$procedures= $device->procedures()->get();
			return response()->json(['success' => 'true','procedures'=>$procedures]);
		}
		else{
			return abort(404);
		}
	}
	
	public function ajaxGetVisitRadiology(Request $request){
		if($request->ajax()){
			// If we add proc ids to devices
			$new_one_rads= Visit::join('medical_order_items','medical_order_items.visit_id','=','visits.id')
							 ->join('procedures','procedures.id','=','medical_order_items.proc_id')
							 ->join('users','users.id','=','medical_order_items.doctor_id')
							 ->join('medical_devices','medical_devices.id','=','procedures.device_id')
							 ->select('medical_order_items.id','medical_devices.name as dev_name','procedures.name as proc_name','users.name as u_name')
							 ->where('visit_id',$request->get('visit_id'))
							 ->where('old_format',false)
							 ->get();
			// Current state old format; we insert proc name 
			$old_one_rads= Visit::join('medical_order_items','medical_order_items.visit_id','=','visits.id')
							 ->join('users','users.id','=','medical_order_items.doctor_id')
							 ->select('medical_order_items.id',DB::raw('"جهاز الأشعة" as dev_name'),'proc_name','users.name as u_name')
							 ->where('visit_id',$request->get('visit_id'))
							 ->where('old_format',true)
							 ->get();
							 
			$merged = array($new_one_rads,$old_one_rads);
			
			if(count($merged) > 0)
				return response()->json(['success' => 'true','data'=>$merged]);
			else
				return response()->json(['success' => 'false']);
		}
		else{
			return abort(404);
		}
	}
	public function ajaxGetVisitMedicine(Request $request){
		if($request->ajax()){
			$medicines= VisitMedicine::join('users','users.id','=','typist_id')
										->select('visit_id','visit_medicines.id','visit_medicines.name', DB::raw('IFNULL(accessories,"") as accessories'),'users.name as username','visit_medicines.created_at')
										->where(array('visit_id'=>$request->get('visit_id')))->get();
			if(count($medicines) > 0)
				return response()->json(['success' => 'true','data'=>$medicines]);
			else
				return response()->json(['success' => 'false']);
		}
		else{
			return abort(404);
		}
	}
	public function ajaxGetDrRecommendation(Request $request){
		if($request->ajax()){
			$visit=Visit::find($request->get('visit_id'));
			if($visit)
				return response()->json(['success' => 'true','content'=>$visit->doctor_recommendation]);
			else
				return response()->json(['success' => 'false']);
		}
		else{
			return abort(404);
		}
	}
	// This function is deprecated 
	public function ajaxGetPatientHistory(Request $request){
		if($request->ajax()){
			$history = DB::table('visits')
						->select(DB::raw('visits.created_at,
						 group_concat(( select concat( case when type="c" then "عيادة " when type="d" then "قسم " end,name)  from medical_units 
						where medical_units.id=medical_unit_visit.medical_unit_id 
						)) as medical_unit,
						( select GROUP_CONCAT(content," ",IFNULL(content_in_english,"")) as content from visit_diagnoses  where visit_diagnoses.visit_id=visits.id
						) as v_dia
						,
						( select GROUP_CONCAT(content) as content from visit_complaints  where visit_complaints.visit_id=visits.id
						) as v_com
						,
						( select GROUP_CONCAT(cure_description) as content from visit_diagnoses  where visit_diagnoses.visit_id=visits.id
						) as v_cure
						,
						( select GROUP_CONCAT(name) as name from visit_medicines  where visit_medicines.visit_id=visits.id
						) as v_med
						,
						( select GROUP_CONCAT((select procedures.name from procedures where procedures.id=medical_order_items.proc_id)) as content from medical_order_items  where medical_order_items.visit_id=visits.id
						) as v_rad'))
						->join('medical_unit_visit','visits.id','=','medical_unit_visit.visit_id')
						->where('visits.patient_id', $request->get('patient_id'))
						->groupBy('visits.id')
						->orderBy('visits.created_at','desc')
						->get();
			
			return response()->json(['success' => 'true','data'=>$history]);
		}
		else{
			return abort(404);
		}
		
	}
	//////////////////////////////////////////
	public function ajaxGetNotSeenVisits(Request $request){
		if($request->ajax()){		
			DB::beginTransaction();
			try{
				$visits = Visit::join('medical_unit_visit', 'visits.id', '=', 'medical_unit_visit.visit_id')
							  ->join('medical_units','medical_unit_visit.medical_unit_id','=','medical_units.id')
							  ->join('patients','patients.id','=','visits.patient_id')
							  ->where('medical_units.id','=', $request->get('clinic_id'))
							  ->where('closed',false)
							  ->where('cancelled',false)
							  ->where('convert_to',null)
							  ->where('seen',false)
							  ->whereDate('visits.created_at','=',date('Y-m-d',time()))
							  ->select('patients.id','patients.name','visits.id as visit_id','medical_units.id as dep_id')
							  ->orderBy('visits.id', 'desc')->get();
				updateSeenAttrForSeenVisits($visits);
				DB::commit();
				return response()->json(['success' => 'true','visits'=>$visits]);
			}
			catch(\Exception $e){
				DB::rollback();
			}
		}
		else{
			return abort(404);
		}
	
	}
	
	public function ajaxGetAllDiagnoses(Request $request){
		
		$diagnoses=VisitDiagnose::select(DB::raw('count(*) as frequency, content'))->orderBy('frequency','desc')->groupBy('content')->take(50)->get();
		return response()->json(['success' => 'true','data'=>$diagnoses]);
		
		
	}
	public function ajaxGetAllComplaints(Request $request){

		$complaints=VisitComplaint::select(DB::raw('count(*) as frequency, content'))->orderBy('frequency','desc')->groupBy('content')->take(50)->get();
		return response()->json(['success' => 'true','data'=>$complaints]);
	
		
	}
	public function ajaxGetAllMedicines(Request $request){
		
		$medicines=VisitMedicine::select(DB::raw('count(*) as frequency, name'))->orderBy('frequency','desc')->groupBy('name')->take(100)->get();
		return response()->json(['success' => 'true','data'=>$medicines]);
		
		
	}

	//////////////////////////////////////////
	public function ajaxRemoveTicketMedicine(Request $request){
		if($request->ajax()){	
			$input = $request->all();	
			if(!isset($input['department']))
				VisitMedicine::find($input['id'])->delete();
			else{
				VisitDiagnose::find($input['id'])->update(
					array('cure_description' => '',
							'accessories' => ''
					)
				);
			}
			return response()->json(['success' => 'true']);
		}
		else{
			return response()->json(['success' => 'false']);
		}
	}

	//////////////////////////////////////////
	public function ajaxRemoveTicketRadiology(Request $request){
		if($request->ajax()){	
			$input = $request->all();	
			MedicalOrderItem::find($input['id'])->delete();
			return response()->json(['success' => 'true']);
		}
		else{
			return response()->json(['success' => 'false']);
		}
	}
}
