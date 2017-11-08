<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\User;
use App\Visit;
use App\Entrypoint;
use App\VisitDiagnose;
use App\VisitComplaint;
use App\VisitMedicine;
use App\MedicalUnit;
use App\MedicalDevice;
use App\Procedure;
use App\Patient;
use App\MedicalOrderItem;
use App\HL7\msgHeader;
use App\HL7\orcOrderInfo;
use App\HL7\patientInfo;
use App\HL7\procedureInfo;
use App\HL7\AUNHHL7ServiceService;
use App\HL7\add;
use App\HL7\addResponse;
use App\Wsconfig;
use Auth;
use Validator;
use Carbon\Carbon;
class VisitController extends Controller
{
    //
	public function index(Request $request,$mid){
		$medical_row=MedicalUnit::find($mid);
		//dd($medical_row);
		if($medical_row['type'] == 'c'){
			//DB::beginTransaction();
			//try{
				$current = Carbon::now();
				$dt = Carbon::now();
				$dt = $dt->subHours(24);
				$visits = Visit::join('medical_unit_visit', 'visits.id', '=', 'medical_unit_visit.visit_id')
						  ->join('medical_units','medical_unit_visit.medical_unit_id','=','medical_units.id')
						  ->join('patients','patients.id','=','visits.patient_id')
						  ->where('medical_units.id','=', $mid)
						  ->where('closed',false)
						  ->where('cancelled',false)
						  ->where('convert_to',null)
						  ->whereBetween('visits.created_at',[$dt,$current])
						  ->select('patients.id','patients.name','visits.id as visit_id','medical_units.id as dep_id')
						  ->orderBy('visits.id', 'desc')->get();
				//updateSeenAttrForSeenVisits($visits);
				// DB::commit();
			// }
			// catch(\Exception $e){
				// DB::rollBack();
			// }

		}
		else{
			$visits = Visit::join('medical_unit_visit', 'visits.id', '=', 'medical_unit_visit.visit_id')
					  ->join('medical_units','medical_unit_visit.medical_unit_id','=','medical_units.id')
					  ->join('patients','patients.id','=','visits.patient_id')
					  ->where('medical_units.id','=', $mid)
					  ->where('closed',false)
					  ->where('cancelled',false)
					  ->where('convert_to',null)
					  ->select('patients.id','patients.name','visits.id as visit_id')
					  ->orderBy('visits.id', 'desc')->get();
		}
		$devices= MedicalDevice::lists('name','id');
		$clinics=MedicalUnit::where('type','=','c')->where('id','!=',$mid)->lists('name', 'id');
		$department_clinic=MedicalUnit::where('id',$medical_row['parent_department_id'])->lists('name', 'id');
		$device_procedures= Procedure::where('device_id',1)->lists('name','id');
		return view('department_visits',array('v_active'=>'active','visits'=>$visits,'devices'=>$devices,'proc'=>$device_procedures,'dep'=>$mid,'medical_type'=>$medical_row['type'],'clinics'=>$clinics,'departments'=>$department_clinic,'medicalunit'=>$medical_row));
	}
	public function store(Request $request,$mid)
	{

		$input=$request->all();
		$user_id=Auth::id();
		if($input['formID'] == 1){
			if($input['status']!=""){
				$messages = [
					'visit.required' => 'من فضلك أختر المريض المراد إنهاء زيارته',
				];
				$this->validate($request, [
					'visit' => 'required',
				],$messages);
				$visit=Visit::find($input['visit']);
				if($visit->diagnoses()->count() == 0 && $visit->complaints()->count() == 0){
					return redirect()->back()->withErrors(['sd'=>'لا يمكن أنهاء الزيارة بدون تسجيل تشخيص أو شكوى']);
				}
				$visit->closed=true;
				$visit->save();
				$request->session()->flash('flash_message', "تم انهاء الزيارة بالنجاح");
			}
			else{
				$medicalUnit=MedicalUnit::find($input['dep']);
				$messages['code.required']='حقل كود المريض مطلوب أدخالة';
				$messages['name.required']='حقل أسم المريض مطلوب أدخالة';
				$messages['complaints.required_without']='حقل الشكوى مطلوب أدخالة';
				$messages['diagnoses.required_without']='حقل التشخيص مطلوب أدخالة';

				$rules['code'] = 'required';
				$rules['name'] = 'required';
				$rules['complaints'] = 'required_without:diagnoses';
				$rules['diagnoses'] = 'required_without:complaints';
				if($medicalUnit->type == 'd')
				{
					$messages['diagnoses.required_without']='حقل التشخيص ( عربي ) مطلوب أدخالة';
					$messages['diagnoses_english.required_without']='حقل التشخيص ( عربي أو أنجليزي ) مطلوب أدخالة';
					$rules['diagnoses_english'] = 'required_without:diagnoses';
					$messages['cure_description.required_with']='حقل العلاج مطلوب أدخالة فى حالة وجود تشخيص فقط';
					$rules['cure_description'] = 'required_with:diagnoses,diagnoses_english';
					$messages['accessories.required_with']='حقل المستلزمات مطلوب أدخالة فى حالة وجود علاج فقط';
					$rules['accessories'] = 'required_with:cure_description';
				}
				$validator = Validator::make($request->all(),$rules,$messages);
				foreach($validator->messages()->all() as $error)
					$error_messages[]=$error;
				if($validator->fails())
					return response()->json(['success' => 'false','messages'=>$error_messages]);


				if($medicalUnit->type == 'c'){

					if($input['diagnoses'] != ""){
						VisitDiagnose::create([
							'visit_id'=>$input['visit'],
							'content'=>$input['diagnoses'],
							'typist_id'=>$user_id
						]);
					}
				}
				else{

					$visit_diagnose= new VisitDiagnose;
					$visit_diagnose->visit_id=$input['visit'];
					$visit_diagnose->typist_id=$user_id;

					if($input['diagnoses'] != "")
						$visit_diagnose->content=$input['diagnoses'];
					if(isset($input['diagnoses_english']) &&  $input['diagnoses_english']!= "")
						$visit_diagnose->content_in_english=$input['diagnoses_english'];
					if(isset($input['cure_description']) && $input['cure_description'] != "")
						$visit_diagnose->cure_description=$input['cure_description'];
					if(isset($input['accessories']) && $input['accessories'] != "")
						$visit_diagnose->accessories=$input['accessories'];
					$visit_diagnose->save();
				}



				if($input['complaints'] != "")
					VisitComplaint::create(['visit_id'=>$input['visit'],'content'=>$input['complaints'],'typist_id'=>$user_id]);
				return response()->json(['success' => 'true','messages'=>"تم التسجيل بالنجاح"]);
			}

		}
		elseif($input['formID'] == 2){
			$messages = [
				'visit.required' => 'من فضلك أختر المريض المراد طلب أشعة',
			];
			$this->validate($request, [
				'visit' => 'required',
			],$messages);
			$data=array('visit_id'=>$input['visit'],'proc_id'=>$input['procedure'],'doctor_id'=>Auth::id());
			DB::beginTransaction();
			$medical_order_item=MedicalOrderItem::create($data);
			$request->session()->flash('flash_message', "تم طلب الأشعة بالنجاح");
			try{
				$this->sendingData($input['visit'],$medical_order_item);
				DB::commit();
			}
			catch (\Exception $e){
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "يوجد مشكلة فى الخدمة حاول مرة اخري");
				DB::rollBack();
			}
		}
		elseif($input['formID'] == 3){
			$messages = [
				'visit.required' => 'من فضلك أختر المريض المراد تحويله',
			];
			$this->validate($request, [
				'visit' => 'required',
			],$messages);
			$visit=Visit::find($input['visit']);
			// name is patient id
			if( $this->checkIfPatientIsExistTodayInClinic($request['name'],$input['clinic']) )
			{
				$request->session()->flash('flash_message', "لا يمكن التحويل لهذة العيادة بسبب وجود حجز فى نفس العيادة");
				$request->session()->flash('message_type', "false");
			}
			else{
				$toClinic=$input['clinic'];
				$fromClinic=MedicalUnit::find($mid);
				DB::beginTransaction();
				try{
					$medicalunitvisit=$fromClinic->visits()->updateExistingPivot($input['visit'],array('convert_to'=>$toClinic));
					$visit->medicalunits()->attach(array($toClinic=>array('user_id'=>Auth::id())));
					DB::commit();
					$request->session()->flash('flash_message', "تم التحويل بالنجاح");
				}
				catch(\Exception $e){
					DB::rollBack();
					$request->session()->flash('flash_message', "حدثت مشكلة حاول مرة اخري");
					$request->session()->flash('message_type', "false");

				}

			}

		}
		elseif($input['formID'] == 4){
			$messages = [
				'visit.required' => 'من فضلك أختر المريض المراد تحويله',
			];
			$this->validate($request, [
				'visit' => 'required',
			],$messages);

			$patient_visits=Patient::find($request['name'])
									 ->visits()
									 ->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
									 ->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
									 ->where('type','c')
									 ->where('medical_units.id','!=',$mid)
									 ->whereDate('visits.created_at','=',date('Y-m-d',time()))
									 ->where('closed',false)->select('visits.id')->get();
			DB::beginTransaction();
			try{
				if(count($patient_visits) > 0){
					foreach($patient_visits as $row){
						$visit=Visit::find($row->id);
						$visit->closed=true;
						$visit->save();
					}
				}
				$visit=Visit::find($input['visit']);
				$toDepartment=$input['department'];
				$fromDepartment=MedicalUnit::find($mid);

				$medicalunitvisit=$fromDepartment->visits()->updateExistingPivot($input['visit'],array('convert_to'=>$toDepartment,'department_conversion'=>true));
				$visit->medicalunits()->attach(array($toDepartment=>array('user_id'=>Auth::id())));
				$request->session()->flash('flash_message', "تم التحويل بالنجاح");
				DB::commit();
			}
			catch(\Exception $e){
				$request->session()->flash('flash_message', "حدثت مشكلة حاول مرة اخري");
				$request->session()->flash('message_type', "false");
				DB::rollBack();
			}

		}
		elseif($input['formID'] == 5){
			$messages = [
				'visit.required' => 'من فضلك أختر المريض المراد تحويله',
				'medicines.required' => 'حقل الدواء مطلوب أدخالة',
			];
			$rules = [
				'visit' => 'required',
				'medicines' => 'required',
			];
			$validator = Validator::make($request->all(),$rules,$messages);
			foreach($validator->messages()->all() as $error)
				$error_messages[]=$error;
			if($validator->fails())
				return response()->json(['success' => 'false','messages'=>$error_messages]);
			VisitMedicine::create(['visit_id'=>$input['visit'],'name'=>$input['medicines'],'accessories'=>$input['accessories']==""?null:$input['accessories'],'typist_id'=>Auth::id()]);
			return response()->json(['success' => 'true','messages'=>"تم التسجيل بالنجاح"]);
		}
		elseif($input['formID'] == 6){
			$messages = [
				'visit.required' => 'من فضلك أختر المريض المراد كتابة التوصية به',
				'medicines.required' => 'حقل الدواء مطلوب أدخالة',
			];
			$rules = [
				'visit' => 'required',
				'dr_recommendation' => 'required',
			];
			$validator = Validator::make($request->all(),$rules,$messages);
			foreach($validator->messages()->all() as $error)
				$error_messages[]=$error;
			if($validator->fails())
				return response()->json(['success' => 'false','messages'=>$error_messages]);

			$visit=Visit::find($input['visit']);
			$visit->doctor_recommendation=$input['dr_recommendation'];
			$visit->save();
			return response()->json(['success' => 'true','messages'=>"تم التسجيل بالنجاح"]);
		}
		return redirect()->action('VisitController@index',array('mid'=>$mid));
	}
	public function deleteVisit($vid){

		if($vid == "")
			return redirect()->back();

		$visit=Visit::find($vid);
		$medical_unit_visits=Visit::join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
								   ->where('visits.id',$vid)
								   ->get();

		if($medical_unit_visits->count() >= 2){
			return redirect()->back()->withError("لا يمكن ألغاء هذا الحجز بسبب تحويل المريض الي عيادة أخري او القسم من جهة الطبيب");
		}
		if($visit->closed == true){
			return redirect()->back()->withError("لا يمكن ألغاء هذا الحجز  و هو قد تم أنهاؤها");
		}
		if($visit->converted_by != null){
			$user=User::find(Auth::id());
			if($user->role->name != "Desk")
				return redirect()->back()->withError("لا يمكن ألغاء هذا الحجز  و هو قد تم تحويل المريض الي مكتب الاستقبال");
		}
		if($visit->created_at->format('Y-m-d') != date('Y-m-d',time())){
			return redirect()->back()->withError("لا يمكن ألغاء حجز بتاريخ سابق");
		}
		if($visit->diagnoses()->count() > 0 || $visit->complaints()->count() > 0 || $visit->orders()->count() > 0 || $visit->medicines()->count() > 0){
			return redirect()->back()->withError("لا يمكن ألغاء هذا الحجز بسبب تسجيل تشخيص أو شكوي أو دواء للمريض من جهة الطبيب");
		}
		DB::beginTransaction();

		try{
			$visit->cancelled=true;
			$visit->ticket_number=0;
			$visit->save();
			DB::commit();
			return redirect()->back()->withSuccess("تم الألغاء بنجاح");
		}
		catch(\Exception $e){
			DB::rollBack();
			return redirect()->back()->withError("حدثت مشكلة أثناء تنفيذ العملية ! حاول مرة أخري");
		}

	}

	public function convertVisit($eid,$vid){

		if($vid == "")
			return redirect()->back();
		if($eid == "")
			return redirect()->back();
		$visit=Visit::find($vid);

		/* $medical_unit_visits=Visit::join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
								   ->where('visits.id',$vid)
								   ->get();

		if($medical_unit_visits->count() >= 2){
			return redirect()->back()->withError("لا يمكن ألغاء هذا الحجز بسبب تحويل المريض الي عيادة أخري او القسم من جهة الطبيب");
		}
		 */
		if($visit->closed == true){
			return redirect()->back()->withError("لا يمكن تحويل المريض بسبب ان الزيادة تم انهاؤها من قبل الطبيب");
		}
		if($visit->converted_by != null){
			return redirect()->back()->withError("لقد تم تحويل المريض من قبل");
		}
		if($visit->created_at->format('Y-m-d') != date('Y-m-d',time())){
			return redirect()->back()->withError("لا يمكن تحويل المريض بتاريخ سابق");
		}
		DB::beginTransaction();

		try{
			$visit->convert_to_entry_id=$eid;
			$visit->converted_by=Auth::id();
			$visit->save();
			DB::commit();
			return redirect()->back()->withSuccess("تم التحويل بنجاح");
		}
		catch(\Exception $e){
			DB::rollBack();
			return redirect()->back()->withError("حدثت مشكلة أثناء تنفيذ العملية ! حاول مرة أخري");
		}

	}

	public function show_inpatient_file_data($vid){
		$visit=Visit::find($vid);
		$patient_name=$visit->patient->name;
		$visit_department=$visit->medicalunits()->get(['name']);
		if($visit->cure_type != null)
			$visit_cure_name=$visit->cure_type->name;
		else
			$visit_cure_name="";
		$default_view="reports.visit_file_report";
		return view($default_view,compact('visit','patient_name','visit_department','visit_cure_name'));
	}

	public function show_inpatient_diagnoses_data($vid){
		$visit=Visit::find($vid);
		$patient_name=$visit->patient->name;
		$visit_diagnoses=$visit->diagnoses()
							   ->join('users','users.id','=','visit_diagnoses.typist_id')
							   ->select('content','content_in_english','visit_diagnoses.created_at as created_at','cure_description','name')
							   ->get();

		$default_view="reports.visit_diagnoses_report";
		return view($default_view,compact('visit','patient_name','visit_diagnoses'));
	}

	public function reportvisit($visitid)
	{
		$visit_data=Visit::leftJoin('users','users.id','=','visits.reference_doctor_id')
		->where('visits.id','=',$visitid)
		->select('visits.exit_status as es','visits.exit_date as ed','visits.final_diagnosis as fd','doctor_recommendation','users.name as name')->get();
		//dd($visit_data);
		return view('report_visit_exit',array('s_active'=>'active','data'=>$visit_data));
	}

	// check if a patient is already exist ( visit closed flag is false ) in the same or another clinic where he/she reserve.
	public function checkIfPatientIsExistTodayInClinic($pid,$mid='')
	{

		if($mid=="")
			$patient_visits=Patient::find($pid)
									 ->visits()
									 ->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
									 ->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
									 ->where('type','c')
									 ->whereDate('visits.created_at','=',date('Y-m-d',time()))
									 ->where('closed',false)
									 ->count();
		else
			$patient_visits=Patient::find($pid)
									 ->visits()
									 ->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
									 ->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
									 ->where('type','c')
									 ->where('medical_units.id',$mid)
									 ->whereDate('visits.created_at','=',date('Y-m-d',time()))
									 ->where('medical_unit_id',$mid)
									 ->where('closed',false)
									 ->count();
		if($patient_visits > 0)
			return true;
		return false;
	}


	// sending data to hl7 webservice
	private function sendingData($visit_id,$medical_order_item){

		$config=Wsconfig::first();
		$msgHeader=new msgHeader();
		$msgHeader->SendingApplication=$config->sending_app;
		$msgHeader->SendingFacility=$config->sending_fac;
		$msgHeader->ReceivingApplication=$config->receiving_app;
		$msgHeader->ReceivingFacility=$config->receiving_fac;

		$visit=Visit::find($visit_id);
		$patient=Patient::find($visit->patient_id);
		$patientInfo=new patientInfo();

		$patientInfo->PatientID="PDREG".$patient->id;
		$patientInfo->PatientBirthdate=$patient->birthdate;
		$patientInfo->PatientGender=$patient->gender;
		$names=explode(" ",$patient->name);
		$patientInfo->LastNamefamilyname=$names[2];
		$patientInfo->FirstName=$names[0];
		$patientInfo->MiddleName=$names[1];
		$patientInfo->Address=$patient->address;
		$patientInfo->PhoneNumber=$patient->phone_num;
		$patientInfo->MaritalStatus="Unknown";
		$patientInfo->Religion="Unknown";
		$patientInfo->NationalID=$patient->sid;
		$patientInfo->Nationality=$patient->nationality;

		$orcOrderInfo=new orcOrderInfo();
		$orcOrderInfo->StudyID=$medical_order_item->id; // string
		$orcOrderInfo->AccesstionNumber= $medical_order_item->id; // string

		$orcOrderInfo->ReceptionistID= $visit->user_id; // string
		$reception_user=User::find($visit->user_id);

		$orcOrderInfo->LastNamefamilynameR= ""; // string
		$orcOrderInfo->FirstNameR= $reception_user->name; // string
		//$orcOrderInfo->MiddleNameR= $names[1]; // string
		//dd($medical_order_item->doctor_id);
		$medicalOrderItem=$medical_order_item;
		//$medical_order_item_object=MedicalOrderItem::find($medicalOrderItem);

		$orcOrderInfo->DoctorID= $medicalOrderItem->doctor_id; // string

		$doctor_user=User::find($medicalOrderItem->doctor_id);


		$orcOrderInfo->LastNamefamilynameDoctor= ""; // string
		$orcOrderInfo->FirstNameDoctor= $doctor_user->name; // string
		//$orcOrderInfo->MiddleNameDoctor= $names[1]; // string

		$procedure=Procedure::find($medicalOrderItem->proc_id);
		$device=$procedure->device->name;
		$orcOrderInfo->ModalityType= ""; // string
		$orcOrderInfo->ModalityName= $device; // string

		$procedureInfo=new procedureInfo();
		$procedureInfo->ProcedureID=$procedure->proc_ris_id; // string
		$procedureInfo->ProcedureName=$procedure->name; // string
		$procedureInfo->ProcedureReason=""; // string
		$procedureInfo->SceduledDateTime=$medicalOrderItem->created_at; // string
		$procedureInfo->StudyID=$medicalOrderItem->id; // string
		$procedureInfo->AccesstionNumber=$medicalOrderItem->id;  // string
		$procedureInfo->DoctorID=$medicalOrderItem->doctor_id; // string
		$procedureInfo->LastNamefamilynameDoctor=""; // string
		$procedureInfo->FirstNameDoctor= $doctor_user->name;  // string
		//$procedureInfo->MiddleNameDoctor=$names[1]; // string

		$client = new AUNHHL7ServiceService();
		$input = new add();
		$input->arg0=$msgHeader;
		$input->arg1=$orcOrderInfo;
		$input->arg2=$patientInfo;
		$input->arg3=$procedureInfo;
		$response=$client->add($input);
		  // var_dump($client->next1($input));

	}
}
