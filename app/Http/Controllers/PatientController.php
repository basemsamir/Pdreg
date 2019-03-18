<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Patient;
use App\Relation;
use App\MedicalUnit;
use App\Visit;
use App\User;
use App\Entrypoint;
use App\CureType;
use App\FileType;
use Auth;
use DB;
use Validator;
use Session;

use Carbon\Carbon;
class PatientController extends Controller
{
    // index action
	public function index(){
		// Restore session after it expired and user return to log in
		$medical_units=MedicalUnit::where('type','d')->lists('name', 'id');
		$first_medical_unit= MedicalUnit::where('type','d')->first();
		$first_department_doctors=$first_medical_unit->users()->lists('users.name','users.id');
		$cure_types=CureType::lists('name','id');
		$file_types=FileType::lists('name','id');
		$user=User::find(Auth::id());
		$entrypoints=$user->entrypoints()->select('name','id')->get();
		$entrypoint_Array=array();
		foreach($user->entrypoints as $row)
			$entrypoint_Array[$row->id]=$row->name;
		$relations= Relation::lists('name','id');
		return view('patient',array('p_active'=>'active','relations'=>$relations,'medical_units'=>$medical_units,'entrypoints'=>$entrypoint_Array,'cure_types'=>$cure_types,'file_types'=>$file_types,'first_department_doctors'=>$first_department_doctors));
	}
	// index for receiption action
	public function indexTicket($pid,Request $request){
		if($pid!="")
		{
			$patient_data=Patient::find($pid);
		}
		$relations=Relation::lists('name', 'id');
		$medical_units=MedicalUnit::where('type','=','c')->lists('name', 'id');
		$user=User::find(Auth::id());
		$entrypoints=$user->entrypoints()->select('name','id')->get();
		$entrypoint_Array=array();

		$ages=array();
		$days = array();
		$ages[""]=0;
		$days[""]=0;
		for($i=1;$i<=11;$i++)
			$ages[$i]=$i;
		for($i=1;$i<=29;$i++)
			$days[$i]=$i;
		foreach($user->entrypoints as $row)
			$entrypoint_Array[$row->id]=$row->name;
		if($pid!="")
			return view('reserve_ticket',array('r_active'=>'active','relations'=>$relations,'ages'=>$ages,'days'=>$days,'medical_units'=>$medical_units,'entrypoints'=>$entrypoint_Array,'patient_data'=>$patient_data));
		else
			return view('reserve_ticket',array('r_active'=>'active','relations'=>$relations,'ages'=>$ages,'days'=>$days,'medical_units'=>$medical_units,'entrypoints'=>$entrypoint_Array));
	}

	// index for receiption action
	public function indexDesk($pid,Request $request){
		if($pid!="")
		{
			$patient_data=Patient::find($pid);
		}
		$relations=Relation::lists('name', 'id');
		$cure_types=CureType::lists('name','id');
		$file_types=FileType::lists('name','id');
		$medical_units=MedicalUnit::where('type','c')->lists('name', 'id');
		$first_medical_unit= MedicalUnit::where('type','c')->first();
		$first_clinic_doctors=$first_medical_unit->users()->lists('users.name','users.id');
		$entrypoint_users=User::where('role_id',4)->lists('name','id');
		$user=User::find(Auth::id());
		$entrypoints=$user->entrypoints()->select('name','id')->get();
		$entrypoints=array();

		$ages=array();
		$days = array();
		$ages[""]=0;
		$days[""]=0;
		for($i=1;$i<=11;$i++)
			$ages[$i]=$i;
		for($i=1;$i<=29;$i++)
			$days[$i]=$i;
		foreach($user->entrypoints as $row)
			$entrypoints[$row->id]=$row->name;
		$desk_active="active";
		if($pid!="" && $pid!="-1")
			return view('desk_ticket',compact('desk_active','relations','ages','days','medical_units','first_clinic_doctors','entrypoints','entrypoint_users','file_types','cure_types','patient_data'));
		else
			return view('desk_ticket',compact('desk_active','relations','ages','days','medical_units','first_clinic_doctors','entrypoints','entrypoint_users','file_types','cure_types'));
	}

	public function showid($id){
		return view('patient',array('p_active'=>'active','id'=>$id));
	}
	public function printdata($id,$vid){
		$patient_visit=Patient::join('visits','visits.patient_id','=','patients.id')
					->join('relations','relations.id','=','visits.relation_id')
					->leftJoin('users','users.id','=','visits.reference_doctor_id')
					->where('patients.id','=',$id)
					->where('visits.id','=',$vid)
					->select('patients.name as pname','gender','birthdate','patients.job as pjob','patients.address as paddress','patients.phone_num as ppnumber','social_status','patients.sid as psid','relations.name as rel_name'
					,'c_name','visits.address as rel_address', 'ticket_number','entry_time',
					'visits.job as rel_job','entry_reason_desc','converted_by_doctor','visits.phone_num as rel_phone','room_number','users.name as reference_doctor_name'
					,'visits.sid as rel_sid','entry_date')
					->get();

		$default_view="report_entry";
		return view($default_view,array('data'=>$patient_visit));
	}
	public function printcarddata($id){
		$patients= Patient::find($id);
		return view('card',array('data'=>$patients));
	}
	public function print_ticket($id){
		$visit=Visit::find($id);
		$created_at=$visit->created_at->format('Y/m/d');
		$patient_data=$visit->patient()
												->select('name','birthdate','address')
												->get();

		$medical_unit= $visit->medicalunits()
												 ->select('name')
												 ->get();

		return view('reports.patient_ticket',compact('patient_data','medical_unit','created_at'));
	}
	public function printhistorydata($id){
		$table_header="السجل المرضي";
		$patient=Patient::find($id);
		if(is_null($patient))
			return abort(404);
		$data= 	DB::table('visits')
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
				( select GROUP_CONCAT(accessories) as name from visit_medicines  where visit_medicines.visit_id=visits.id
				) as v_access_clinic
				,
				( select GROUP_CONCAT(accessories) as name from visit_diagnoses  where visit_diagnoses.visit_id=visits.id
				) as v_access_dep
				'))
				->join('medical_unit_visit','visits.id','=','medical_unit_visit.visit_id')
				->where('visits.patient_id', $id)
				->where('visits.cancelled',false)
				->groupBy('visits.id')
				->orderBy('visits.created_at','desc')
				->get();
	/**
	 Select xray in sql
		( select GROUP_CONCAT((select procedures.name from procedures where procedures.id=medical_order_items.proc_id)) as content from medical_order_items  where medical_order_items.visit_id=visits.id
		) as v_rad
	*/
	//	dd($data);
		return view('reports.patient_history',compact('data','patient','table_header'));
	}
	public function store(Request $request)
	{
		$input=$request->all();
		// check if a patient is already exist in a clinic.
		if($input['patient_id'] != "" && $this->checkIfPatientIsExistTodayInClinic($input['patient_id']) > 0)
		{
			$request->session()->flash('message_type', "false");
			$request->session()->flash('flash_message', "هذا المريض مسجل فى حجز عيادة و لم يتم انهاء زيارته");
		}
		else{
			// check if a patient is already exist in a department.
			if($input['patient_id'] != "" && $this->checkIfPatientIsExistInDepartment($input['patient_id']) > 0)
			{
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "هذا المريض موجود فى القسم الداخلي و لم يتم اخراجه");
			}
			else{

				// check if a patient is recorded before or not to insert or update
				if($input['patient_id']== "")
				{
					$messages["fname.required"]='هؤلاء الحقول مطلوب الأدخال بشكل كامل';
					$messages["fname.min"]='حقل الأسم الأول يجب الأ يقل عن :min حروف';
					$messages["fname.max"]='حقل الأسم الأول يجب الأ يتعدي :max حرف';
					$messages["fname.alpha"]='هذا الحقل يجب أن يكون حروف فقط بدون وجود مسافات';
					$messages["sname.required"]='هؤلاء الحقول مطلوب الأدخال بشكل كامل';
					$messages["sname.min"]='حقل الأسم الثاني يجب الأ يقل عن :min حروف';
					$messages["sname.max"]='حقل الأسم الثاني يجب الأ يتعدي :max حرف';
					$messages["sname.alpha"]='هذا الحقل يجب أن يكون حروف فقط بدون وجود مسافات';
					$messages["mname.required"]='هؤلاء الحقول مطلوب الأدخال بشكل كامل';
					$messages["mname.min"]='حقل الأسم الثالث يجب الأ يقل عن :min حروف';
					$messages["mname.max"]='حقل الأسم الثالث يجب الأ يتعدي :max حرف';
					$messages["mname.alpha"]='هذا الحقل يجب أن يكون حروف فقط بدون وجود مسافات';
					$messages["lname.required"]='هؤلاء الحقول مطلوب الأدخال بشكل كامل';
					$messages["lname.min"]='حقل الأسم الرابع يجب الأ يقل عن :min حروف';
					$messages["lname.max"]='حقل الأسم الرابع يجب الأ يتعدي :max حرف';
					$messages["gender.required"]='هؤلاء الحقول مطلوب الأدخال بشكل كامل';
					$messages["sid.required"]='هؤلاء الحقول مطلوب الأدخال بشكل كامل';
					$messages['sid.sin_format'] =  'رقم البطاقة غير صحيح.';
					$messages["sid.unique"]='رقم البطاقة موجود من قبل';
					$messages['birthdate.required'] = 'هذا الحقل مطلوب الأدخال.';
					$messages['birthdate.date'] = 'حقل تاريخ الميلاد يجب أن يكون تاريخ';
					$messages['address.required'] = 'هذا الحقل مطلوب الأدخال';
					$messages['address.min'] = 'حقل العنوان يجب الأ يقل عن :min حروف';

					$constraints['fname']='required|alpha|min:2|max:20';
					$constraints['sname']='required|alpha|min:2|max:20';
					$constraints['lname']='required|alpha|min:2|max:20';
					$constraints['mname']='required|alpha|min:2|max:20';
					$constraints['gender']='required';
					$constraints['sid']='required|sin_format|unique:patients,sid';
					$constraints['birthdate'] = 'required|date';
					$constraints['address']='required|min:3';
				}
				$messages['phone_num.max'] = 'حقل رقم التليفون يجب الأ يتعدي :max رقم';
				$messages['phone_num.min'] = 'حقل رقم التليفون يجب الأ يقل عن :min أرقام';
				$messages['social_status.min']='حقل الحالة الاجتماعية يجب الأ يقل عن :min حروف';
				$messages['e_sid.sin_format'] =  'رقم البطاقة غير صحيح.';
				$messages['e_sid.different'] = 'حقل رقم بطاقة المرافق يجب أن يكون مختلف عن رقم بطاقة المريض';
				$messages['c_name.required'] = 'هذا الحقل مطلوب الأدخال';
				$messages['c_name.min'] ='هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['c_name.max'] = 'هذا الحقل يجب الأ يتعدي :max حرف';
				$messages['m_address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['m_job.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['m_phone_num.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['m_phone_num.max'] = 'هذا الحقل يجب الأ يتعدي :max حرف';
				$messages['entry_id.required'] = 'هذا الحقل مطلوب الأدخال';
				$messages['medical_id.required'] = 'هذا الحقل مطلوب الأدخال';
				$messages['entry_date.required'] = 'هذا الحقل مطلوب الأدخال';
				$messages['entry_time.required'] = 'هذا الحقل مطلوب الأدخال';

				$constraints['phone_num']='min:4|max:20';
				$constraints['social_status']='min:4';
				if($input['sid']!="")
					$constraints['e_sid']='sin_format|different:sid';
				else
					$constraints['e_sid']='sin_format';
				$constraints['c_name']='required|min:2|max:50';
				$constraints['m_address']='min:3';
				$constraints['m_job']='min:3';
				$constraints['m_phone_num']='min:4|max:20';
				$constraints['entry_id']='required';
				$constraints['medical_id']='required';
				$constraints['entry_date']='required';
				$constraints['entry_time']='required';

				$input=$request->all();
				$this->validate($request,$constraints,$messages);
				DB::beginTransaction();
				try{
					if($input['patient_id']== ""){
						$input['name']=$input['fname']." ".$input['sname']." ".$input['mname']." ".$input['lname'];
						unset($input['fname']);unset($input['sname']);unset($input['mname']);unset($input['lname']);
						$patient_input=
						array(
							'sid'=>$input['sid'],
							'name'=>$input['name'],
							'gender'=>$input['gender'],
							'social_status'=>$input['social_status']==""?null:$input['social_status'],
							'address'=>$input['address']==""?null:$input['address'],
							'phone_num'=>$input['phone_num']==""?null:$input['phone_num'],
							'birthdate'=>$input['birthdate'],
							'age'=>$input['age'],
							'job'=>$input['job']==""?null:$input['job'],
						);
						$the_last_id=Patient::orderBy('id', 'desc')->first();
						$patient_input['id']=($the_last_id->id==null)?1:$the_last_id->id+1;
						$patient_object=Patient::create($patient_input);
						$pid=$patient_object->id;
					}
					else{
						$exist_patient=Patient::find($input['patient_id']);
						$exist_patient->address=$input['address']==""?null:$input['address'];
						$exist_patient->social_status=$input['social_status']==""?null:$input['social_status'];
						$exist_patient->phone_num=$input['phone_num']==""?null:$input['phone_num'];
						$exist_patient->job=$input['job']==""?null:$input['job'];
						$exist_patient->save();
						$pid=$exist_patient->id;
					}
					$visit_input_data=
					array(
						'patient_id'=>$pid,
						'ticket_num'=>0,
						'c_name'=>$input['c_name'],
						'sid'=>$input['e_sid']==""?null:$input['e_sid'],
						'relation_id'=>$input['relation_id']==""?null:$input['relation_id'],
						'address'=>$input['m_address']==""?null:$input['m_address'],
						'job'=>$input['m_job']==""?null:$input['m_job'],
						'phone_num'=>$input['m_phone_num']==""?null:$input['m_phone_num'],
						'converted_by_doctor'=>$input['converted_by_doctor']==""?null:$input['converted_by_doctor'],
						'entry_time'=>$input['entry_time'],
						'room_number'=>$input['room_number']==""?null:$input['room_number'],
						'file_number'=>$input['file_number'],
						'file_type'=>$input['file_type'],
						'cure_type_id'=>$input['cure_type_id']==""?null:$input['cure_type_id'],
						'contract'=>$input['contract']==""?null:$input['contract'],
						'reference_doctor_id'=>$input['reference_doctor_id']==""?null:$input['reference_doctor_id'],
						'entry_date'=>$input['entry_date'],

					);
					$visit_input_data['user_id']=Auth::id();
					$visit_input_data['entry_id']=$input['entry_id'];
					$visit=Visit::create($visit_input_data);
					$medicalunits = $input['medical_id'];
					$visit->medicalunits()->attach($medicalunits);
					$vid=$visit->id;
					DB::commit();
					$request->session()->flash('flash_message', "تم حفظ بيانات المريض بنجاح - كود المريض : $pid ");
					$request->session()->flash('message_type', "true");
					$request->session()->flash('id', $pid);
					$request->session()->flash('vid', $vid);
				}
				catch(\Exception $e){
					DB::rollback();
					$request->session()->flash('flash_message', " يوجد خطأ فى عملية الأدخال حاول مرة أخرى ");
				}
			}
		}
		return redirect()->action('PatientController@index');
	}

	public function storeTicket(Request $request,$pid)
	{
		$input=$request->all();

		// If code is not empty, it means that the patient data is already exist

		if(isset($input['id']) && $input['id'] != "")
		{
			// check if a patient is already exist ( visit closed flag is false ) in the same clinic where he/she reserves.
			if($this->checkIfPatientIsExistTodayInClinic($input['id'],$input['medical_id']) > 0)
			{
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "هذا المريض تم تسجيل له هذا الحجز و لم يتم أنهاءه");
			}
			else{
				// check if a patient is already exist in a department.
				if($this->checkIfPatientIsExistInDepartment($input['id']) > 0)
				{
					$request->session()->flash('message_type', "false");
					$request->session()->flash('flash_message', "هذا المريض موجود فى القسم الداخلي و لم يتم اخراجه");
				}
				else{

					$desk_ticket=Visit::where('ticket_number',$input['ticket_num'])
														->whereNotNull('ticket_type')
														->first();
					$messages=[
						'ticket_num.required' => 'هذا الحقل مطلوب الأدخال.',
						'ticket_num.numeric' => 'حقل رقم التذكرة يجب أن يكون رقم فقط .',
						'ticket_num.unique' => 'رقم التذكرة موجود من قبل .',
						'entry.required' => 'هذا الحقل مطلوب الأدخال.',
						'medical_id.required' => 'هذا الحقل مطلوب الأدخال.',
					];
					if(count($desk_ticket) > 0)
						$constraints['ticket_num']='required|numeric|unique:visits,ticket_number,'.$desk_ticket->id;
					else {
						$constraints['ticket_num']='required|numeric|unique:visits,ticket_number';
					}

					$constraints['entry']='required';
					$constraints['medical_id']='required';
					if($input['reservation_type'] == "T&E"){

						$messages['c_name.required']= 'هذا الحقل مطلوب الأدخال.';
						$messages['c_name.min']= 'هذا الحقل يجب الأ يقل عن :min حروف';
						$messages['c_name.max']= 'هذا الحقل يجب الأ يتعدي :max حرف';
						$messages['relation_id.required']= 'هذا الحقل مطلوب الأدخال.';
						$messages['c_address.required']= 'هذا الحقل مطلوب الأدخال.';
						$messages['c_address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
						$messages['job.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
						$messages['c_sid.required']= 'هذا الحقل مطلوب الأدخال.';
						$messages['c_sid.size'] = 'حقل رقم البطاقة الخاص بالمرافق يجب أن يكون مكون من :size رقم.';
						$messages['c_sid.different'] = 'حقل رقم بطاقة المرافق يجب أن يكون مختلف عن رقم بطاقة المريض.';
						$messages['entry_reason_desc.required']= 'هذا الحقل مطلوب الأدخال.';
						$messages['entry_time.required']= 'هذا الحقل مطلوب الأدخال.';

						$constraints['c_name']='required|min:2|max:50';
						$constraints['relation_id']='required';
						$constraints['c_address']='required|min:2';
						$constraints['job']='min:2';
						$constraints['c_sid']='required|size:14|different:hidden_sid';
						$constraints['entry_reason_desc']='required';
						$constraints['entry_time']='required';
					}
					$this->validate($request, $constraints ,$messages);

					if($input['reservation_type'] == "T&E"){
						$arr['c_sid']=return_birthdate($input['c_sid']);
						$messages = [
							'c_sid.date' => 'الرقم القومي غير صحيح'
						];
						$validator = Validator::make($arr, [
						  'c_sid' => 'date',
					  ],$messages);

					  if ($validator->fails()) {
						  return redirect()->back()
											 ->withErrors($validator)
											 ->withInput();
					  }
					}
					$visit_input_data=
					array(
						'patient_id'=>$input['id'],
						'ticket_number'=>$input['ticket_num'],
						'user_id'=>Auth::id(),
						'entry_id'=>$input['entry']
					);
					if($input['reservation_type'] == "T&E"){
						$visit_input_data['c_name']=$input['c_name'];
						$visit_input_data['sid']=$input['c_sid'];
						$visit_input_data['relation_id']=$input['relation_id'];
						$visit_input_data['address']=$input['c_address'];
						$visit_input_data['job']=$input['job']==""?null:$input['job'];
						$visit_input_data['entry_time']=$input['entry_time'];
						$visit_input_data['entry_reason_desc']=$input['entry_reason_desc'];
						$visit_input_data['entry_date']=date('Y-m-d');
					}
					$visit=Visit::create($visit_input_data);
					if($input['reservation_type'] == "T&E"){
						// Get the attached department to chosen clinic
						$department_id= MedicalUnit::find($input['medical_id']);
						if(is_null($department_id->parent_department_id)){
								return redirect()->back()
																->withFlashMessage( "يجب أن يكون للعيادة القسم الخاص بها لكي يتم عملية دخول المريض")
																->withMessageType("false")
																->withInput();
						}
						$visit->medicalunits()->attach($department_id->parent_department_id);
					}
					else{
						$visit->medicalunits()->attach($input['medical_id']);
					}
					$request->session()->flash('flash_message', "تم التسجيل بنجاح <br> كود المريض : $input[id] <br> تذكرة المريض: $input[ticket_num]");
					$request->session()->flash('vid', $visit->id);
				}
			}
		}
		else{
			$desk_ticket=Visit::where('ticket_number',$input['ticket_num'])
												->whereNotNull('ticket_type')
												->first();
			$messages = [
				'fname.required' => 'هؤلاء الحقول مطلوب الأدخال بشكل كامل.',
				'fname.min' => 'حقل الأسم الأول يجب الأ يقل عن :min حروف',
				'fname.max' => 'حقل الأسم الأول يجب الأ يتعدي :max حرف',
				'fname.alpha' =>'حقل الأسم الأول يجب أن يكون حروف فقط بدون وجود مسافات.',
				'sname.required' => 'هؤلاء الحقول مطلوب الأدخال بشكل كامل.',
				'sname.min' => 'حقل الأسم الثاني يجب الأ يقل عن :min حروف',
				'sname.max' => 'حقل الأسم الثاني يجب الأ يتعدي :max حرف',
				'sname.alpha' =>'حقل الأسم الثاني أن يكون حروف فقط بدون وجود مسافات.',
				'mname.required' => 'هؤلاء الحقول مطلوب الأدخال بشكل كامل.',
				'mname.min' => 'حقل الأسم الثالث يجب الأ يقل عن :min حروف',
				'mname.max' => 'حقل الأسم الثالث يجب الأ يتعدي :max حرف',
				'mname.alpha' =>'حقل الأسم الثالث أن يكون حروف فقط بدون وجود مسافات.',
				'lname.min' => 'حقل الأسم الرابع يجب الأ يقل عن :min حروف',
				'lname.max' => 'حقل الأسم الرابع يجب الأ يتعدي :max حرف',
				'lname.alpha' =>'حقل الأسم الرابع أن يكون حروف فقط بدون وجود مسافات.',
				'gender.required' => 'هذا الحقل مطلوب الأدخال.',
				'address.required' => 'هذا الحقل مطلوب الأدخال.',
				'sid.size' => 'حقل رقم البطاقة يجب أن يكون مكون من :size رقم.',
				'sid.unique' => 'رقم البطاقة موجود من قبل.',
				'year_age.numeric' => 'حقل عدد السنين يجب ان يكون رقم فقط.',
				'year_age.required_without_all' => 'حقل عدد السنين يجب أن يكون أكبر من 0 فى حالة عدم وجود عدد أيام أو عدد أشهر',
				'ticket_num.required' => 'هذا الحقل مطلوب الأدخال.',
				'ticket_num.numeric' => 'حقل رقم التذكرة يجب أن يكون رقم فقط .',
				'ticket_num.unique' => 'رقم التذكرة موجود من قبل .',
				'entry.required' => 'هذا الحقل مطلوب الأدخال.',
				'medical_id.required' => 'هذا الحقل مطلوب الأدخال.',
				'reservation_type.required' => 'هذا الحقل مطلوب الأدخال.',
			];
			//dd($input['ticket_num']);
				$constraints['fname'] = 'required|alpha|min:2|max:20';
				$constraints['sname'] = 'required|alpha|min:2|max:20';
				$constraints['mname'] = 'required|alpha|min:2|max:20';
				$constraints['lname'] = 'alpha|min:2|max:20';
				$constraints['gender'] = 'required';
				$constraints['address'] = 'required';
				$constraints['sid'] = 'size:14|unique:patients,sid';
				$constraints['year_age'] = 'numeric|required_without_all:month_age,day_age';

				if(count($desk_ticket) > 0)
					$constraints['ticket_num']='required|numeric|unique:visits,ticket_number,'.$desk_ticket->id;
				else {
					$constraints['ticket_num']='required|numeric|unique:visits,ticket_number';
				}


				$constraints['entry']='required';
				$constraints['medical_id']='required';
				$constraints['reservation_type']='required';


			if($input['reservation_type'] == "T&E"){

				$messages['c_name.required']= 'هذا الحقل مطلوب الأدخال.';
				$messages['c_name.min']= 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['c_name.max']= 'هذا الحقل يجب الأ يتعدي :max حرف';
				$messages['relation_id.required']= 'هذا الحقل مطلوب الأدخال.';
				$messages['c_address.required']= 'هذا الحقل مطلوب الأدخال.';
				$messages['c_address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['job.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['c_sid.required']= 'هذا الحقل مطلوب الأدخال.';
				$messages['c_sid.size'] = 'حقل رقم البطاقة الخاص بالمرافق يجب أن يكون مكون من :size رقم.';
				$messages['c_sid.different'] = 'حقل رقم بطاقة المرافق يجب أن يكون مختلف عن رقم بطاقة المريض.';
				$messages['entry_reason_desc.required']= 'هذا الحقل مطلوب الأدخال.';
				$messages['entry_time.required']= 'هذا الحقل مطلوب الأدخال.';

				$constraints['c_name']='required|min:2|max:50';
				$constraints['relation_id']='required';
				$constraints['c_address']='required|min:2';
				$constraints['job']='min:2';
				$constraints['c_sid']='required|size:14|different:hidden_sid';
				$constraints['entry_reason_desc']='required';
				$constraints['entry_time']='required';
			}
			$this->validate($request, $constraints ,$messages);

			$input['name']=$input['fname']." ".$input['sname']." ".$input['mname']." ".$input['lname'];
			unset($input['fname']);unset($input['sname']);unset($input['mname']);unset($input['lname']);

			if($input['sid'] != ""){

				$arr['sid']=return_birthdate($input['sid']);
				$messages = [
					'sid.date' => 'الرقم القومي غير صحيح'
				];
				$validator = Validator::make($arr, [
					'sid' => 'date',
				],$messages);

				if ($validator->fails()) {
					return redirect()->back()
										 ->withErrors($validator)
										 ->withInput();
				}
				$birthdate=$arr['sid'];
				$patient_input=
				array(
					'sid'=>$input['sid'],
					'name'=>$input['name'],
					'gender'=>$input['gender'],
					'address'=>$input['address'],
					'birthdate'=>$birthdate,
					'age'=>$input['year_age'],
				);
			}
			else{
				$birthdate=strtotime("-".($input['day_age']==""?0:$input['day_age'])." day",time());
				$birthdate=strtotime("-".($input['month_age']==""?0:$input['month_age'])." month",$birthdate);
				$birthdate=strtotime("-".($input['year_age']==""?0:$input['year_age'])." year",$birthdate);

				$birthdate=date('Y-m-d',$birthdate);
				$patient_input=
				array(
					'name'=>$input['name'],
					'gender'=>$input['gender'],
					'address'=>$input['address'],
					'birthdate'=>$birthdate,
					'age'=>$input['year_age'],
				);

			}
			if($input['reservation_type'] == "T&E"){
				$arr['c_sid']=return_birthdate($input['c_sid']);
				$messages = [
					'c_sid.date' => 'الرقم القومي غير صحيح'
				];
				$validator = Validator::make($arr, [
	          'c_sid' => 'date',
	      ],$messages);

	      if ($validator->fails()) {
	          return redirect()->back()
	                      		 ->withErrors($validator)
	                      		 ->withInput();
	      }
			}

			DB::beginTransaction();

			try{
				$the_last_id=Patient::orderBy('id', 'desc')->first();
				$patient_input['id']=($the_last_id->id==null)?1:$the_last_id->id+1;
				$patient_object=Patient::create($patient_input);
				$pid=$patient_object->id;
				$visit_input_data=
				array(
					'patient_id'=>$pid,
					'ticket_number'=>$input['ticket_num'],
					'user_id'=>Auth::id(),
					'entry_id'=>$input['entry']
				);
				if($input['reservation_type'] == "T&E"){
					$visit_input_data['c_name']=$input['c_name'];
					$visit_input_data['sid']=$input['c_sid'];
					$visit_input_data['relation_id']=$input['relation_id'];
					$visit_input_data['address']=$input['c_address'];
					$visit_input_data['job']=$input['job']==""?null:$input['job'];
					$visit_input_data['entry_time']=$input['entry_time'];
					$visit_input_data['entry_reason_desc']=$input['entry_reason_desc'];
					$visit_input_data['entry_date']=date('Y-m-d');
				}
				$visit=Visit::create($visit_input_data);
				if($input['reservation_type'] == "T&E"){
					// Get the attached department to chosen clinic
					$department_id= MedicalUnit::find($input['medical_id']);
					if(is_null($department_id->parent_department_id)){
							DB::rollback();
							return redirect()->back()
															->withFlashMessage( "يجب أن يكون للعيادة القسم الخاص بها لكي يتم عملية دخول المريض")
															->withMessageType("false")
															->withInput();
					}
					$visit->medicalunits()->attach($department_id->parent_department_id);
				}
				else{
					$visit->medicalunits()->attach($input['medical_id']);
				}
				DB::commit();
				$request->session()->flash('flash_message', "تم التسجيل بنجاح <br> كود المريض : $pid <br> تذكرة المريض: $input[ticket_num]");
				$request->session()->flash('vid',$visit->id);
			}
			catch(\Exception $e){
				DB::rollback();
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "حدثت مشكلة فى أدخال البيانات! حاول مرة أخرى");
			}
		}
		return redirect()->action('PatientController@indexTicket',array('pid'=>-1));

	}


	public function storeDesk(Request $request,$pid)
	{
		$input=$request->all();
		// If code is not empty, it means that the patient data is already exist
		//dd($input);
		if(isset($input['id']) && $input['id'] != "")
		{
			// check if a patient is already exist ( visit closed flag is false ) in the same clinic where he/she reserves.
			if($this->checkIfPatientIsExistTodayInClinic($input['id'],$input['medical_id'],(isset($input['all_deps'])),$input['reg_date']) > 0)
			{
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "هذا المريض تم تسجيل له هذا الحجز في هذة العيادة و لم يتم أنهاءه");
			}
			else{
				// check if a patient is already exist in a department.
				if($this->checkIfPatientIsExistInDepartment($input['id']) > 0)
				{
					$request->session()->flash('message_type', "false");
					$request->session()->flash('flash_message', "هذا المريض موجود فى القسم الداخلي و لم يتم اخراجه");
				}
				else{
					if($input['ticket_status'] == "T")
						$clinic_ticket=Visit::where('ticket_number',$input['ticket_num'])
															->whereNull('ticket_type')
															->first();
					$messages=[
						'ticket_num.required' => 'هذا الحقل مطلوب الأدخال.',
						'ticket_num.numeric' => 'حقل رقم التذكرة يجب أن يكون رقم فقط .',
						'ticket_num.unique' => 'رقم التذكرة موجود من قبل .',
						'ticket_type.required' => 'هذا الحقل مطلوب الأدخال.',
						'serial_number.required' => 'هذا الحقل مطلوب الأدخال.',
						'reg_date.required' => 'هذا الحقل مطلوب الأدخال.',
						'entry.required' => 'هذا الحقل مطلوب الأدخال.',
						'medical_id.required' => 'هذا الحقل مطلوب الأدخال.',
						'reservation_type.required' => 'هذا الحقل مطلوب الأدخال.',
						'sent_by_person.required' => 'هذا الحقل مطلوب الأدخال.',
						'ticket_companion_name.required_with' => 'هذا الحقل مطلوب فى حال وجود رقم البطاقة',
						'ticket_companion_sin.sin_format' => 'رقم البطاقة غير صحيح.',
						'ticket_companion_sin.different' => 'رقم البطاقة يجب ان يكون مختلف عن رقم بطاقة المريض',
						'sid.sin_format' => 'رقم البطاقة غير صحيح.',
					];
					
					/* in case of ticket status ("T"=> number, "F"=> free word) is T, user types ticket numbar 
					and the oposite condition is F, user types free word */ 
					if($input['ticket_status'] == "T"){
						if(count($clinic_ticket) > 0){
							$constraints['ticket_num']='required|numeric|unique:visits,ticket_number,'.$clinic_ticket->id;
						}
						else{
							$constraints['ticket_num']='required|numeric|unique:visits,ticket_number';
						}
							
					}
					$constraints['ticket_type']='required';
					$constraints['entry']='required';
					$constraints['medical_id']='required';
					$constraints['reservation_type']='required';
					$constraints['sent_by_person']='required';
					$constraints['ticket_companion_name']='required_with:ticket_companion_sin';
					$constraints['ticket_companion_sin']='sin_format|different:sid';
					if(isset($input['sid'])){
						$constraints['sid']='sin_format';
					}
					if($input['reservation_type'] == "T&E"){
						
						$messages['c_name.required_with'] = 'هذا الحقل مطلوب فى حال وجود رقم البطاقة';
						$messages['c_name.min']= 'هذا الحقل يجب الأ يقل عن :min حروف';
						$messages['c_name.max']= 'هذا الحقل يجب الأ يتعدي :max حرف';
						$messages['c_address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
						$messages['c_job.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
						$messages['c_sid.different'] = 'حقل رقم بطاقة المرافق يجب أن يكون مختلف عن رقم بطاقة المريض.';
						$messages['c_sid.sin_format'] = 'رقم البطاقة غير صحيح.';
						$messages['file_number.required'] = 'هذا الحقل مطلوب الأدخال.';
						$messages['file_number.unique'] = 'هذا الرقم موجود من قبل';
						$messages['file_type.required'] = 'هذا الحقل مطلوب الأدخال';
						$messages['entry_date.date'] = 'هذا الحقل يجب أن يكون تاريخ';
						$messages['entry_date.after'] = 'تاريخ الدخول يجب أن يكون أكبر من تاريخ التسجيل.';
						
						$constraints['entry_date']='date|after_or_equal_date:reg_date';
						$constraints['c_name']='required_with:c_sid|min:2|max:50';
						$constraints['c_address']='min:2';
						$constraints['c_job']='min:2';
						$constraints['c_sid']='sin_format|different:sid';
						$constraints['file_number']='required|unique:visits,file_number';
						$constraints['file_type']='required';
					}
					$this->validate($request, $constraints ,$messages);
					$patient_data=array();
					if(isset($input['sid']) && $input['sid']!=""){
						$birthdate=return_birthdate($input['sid']);
						$patient_data['sid']=$input['sid'];
						$patient_data['birthdate']=$birthdate;
					}
					if(isset($input['address'])){
						$patient_data['address']=$input['address'];
					}
					if(isset($input['job'])){
						$patient_data['job']=$input['job'];
					}
					DB::beginTransaction();
					try{
						Patient::find($input['id'])->update($patient_data);
						$visit_input_data=
						array(
							'patient_id'=>$input['id'],
							'serial_number'=>$input['serial_number'],
							'ticket_number'=>$input['ticket_status'] == "T"?$input['ticket_num']:"مجاني",
							'ticket_status'=>$input['ticket_status'],
							'ticket_type'=>$input['ticket_type'],
							'user_id'=>Auth::id(),
							'entry_id'=>$input['entry'],
							'registration_datetime'=>Carbon::parse($input['reg_date']." ".$input['reg_time']),
							'watching_status'=>$input['watching_status']==""?null:$input['watching_status'],
							'sent_by_person'=>$input['sent_by_person'],
							'ticket_companion_name'=>$input['ticket_companion_name']==""?null:$input['ticket_companion_name'],
							'ticket_companion_sin'=>$input['ticket_companion_sin']==""?null:$input['ticket_companion_sin']
						);
						if($input['reservation_type'] == "T&E"){
							$visit_input_data['c_name']=$input['c_name']==""?null:$input['c_name'];
							$visit_input_data['sid']=$input['c_sid']==""?null:$input['c_sid'];
							$visit_input_data['relation_id']=$input['relation_id']==""?null:$input['relation_id'];
							$visit_input_data['address']=$input['c_address']==""?null:$input['c_address'];
							$visit_input_data['job']=$input['c_job']==""?null:$input['c_job'];
							$visit_input_data['room_number']=$input['room_number']==""?null:$input['room_number'];
							$visit_input_data['file_number']=$input['file_number'];
							$visit_input_data['file_type']=$input['file_type'];
							$visit_input_data['cure_type_id']=$input['cure_type_id']==""?null:$input['cure_type_id'];
							$visit_input_data['contract']=$input['contract']==""?null:$input['contract'];
							$visit_input_data['converted_by_doctor']=$input['converted_by_doctor']==""?null:$input['converted_by_doctor'];
							$visit_input_data['reference_doctor_id']=$input['reference_doctor_id']==""?null:$input['reference_doctor_id'];
							
		
							$visit_input_data['entry_date']=$input['entry_date']==""?$input['reg_date']:$input['entry_date'];
							$visit_input_data['entry_time']=$input['entry_time']==""?$input['reg_time']:$input['entry_time'];
						
						}
						$visit=Visit::create($visit_input_data);
						if($input['reservation_type'] == "T&E"){
							// Get the attached department to chosen clinic
							$department_id= MedicalUnit::find($input['medical_id']);
							if(is_null($department_id->parent_department_id)){
									return redirect()->back()
													->withFlashMessage( "يجب أن يكون للعيادة القسم الخاص بها لكي يتم عملية دخول المريض")
													->withMessageType("false")
													->withInput();
							}
							$visit->medicalunits()->attach($department_id->parent_department_id);
						}
						else{

							if(isset($input['all_deps'])){								
								$visit->all_deps=true;
								$visit->save();
								$all_clinics=MedicalUnit::where('type','c')->lists('id');
								$all_clinics=$all_clinics->toArray();
								$visit->medicalunits()->attach($all_clinics);
								
							}
							else{
								$visit->medicalunits()->attach($input['medical_id']);
							}

						}
						DB::commit();
						$request->session()->flash('flash_message', "تم التسجيل بنجاح <br> كود المريض : $input[id] <br> تذكرة المريض : $visit_input_data[ticket_number]");
						$request->session()->flash('vid', $visit->id);
					}
					catch(\Expection $e){

						DB::rollback();
					}
					
				}
			}
		}
		else{

			$messages = [
				'fname.required' => 'هؤلاء الحقول مطلوب الأدخال بشكل كامل.',
				'fname.min' => 'حقل الأسم الأول يجب الأ يقل عن :min حروف',
				'fname.max' => 'حقل الأسم الأول يجب الأ يتعدي :max حرف',
				'fname.alpha' =>'حقل الأسم الأول يجب أن يكون حروف فقط بدون وجود مسافات.',
				'sname.required' => 'هؤلاء الحقول مطلوب الأدخال بشكل كامل.',
				'sname.min' => 'حقل الأسم الثاني يجب الأ يقل عن :min حروف',
				'sname.max' => 'حقل الأسم الثاني يجب الأ يتعدي :max حرف',
				'sname.alpha' =>'حقل الأسم الثاني أن يكون حروف فقط بدون وجود مسافات.',
				'mname.required' => 'هؤلاء الحقول مطلوب الأدخال بشكل كامل.',
				'mname.min' => 'حقل الأسم الثالث يجب الأ يقل عن :min حروف',
				'mname.max' => 'حقل الأسم الثالث يجب الأ يتعدي :max حرف',
				'mname.alpha' =>'حقل الأسم الثالث أن يكون حروف فقط بدون وجود مسافات.',
				'lname.min' => 'حقل الأسم الرابع يجب الأ يقل عن :min حروف',
				'lname.max' => 'حقل الأسم الرابع يجب الأ يتعدي :max حرف',
				'lname.alpha' =>'حقل الأسم الرابع أن يكون حروف فقط بدون وجود مسافات.',
				'serial_number.required' => 'هذا الحقل مطلوب الأدخال.',
				'reg_date.required' => 'هذا الحقل مطلوب الأدخال.',
				'reg_date.date' => 'هذا الحقل يجب أن يكون تاريخ',
				'gender.required' => 'هذا الحقل مطلوب الأدخال.',
				'address.required' => 'هذا الحقل مطلوب الأدخال.',
				'job.required' => 'هذا الحقل مطلوب الأدخال.',
				'sid.sin_format' => 'رقم البطاقة غير صحيح.',
				'sid.unique' => 'رقم البطاقة موجود من قبل.',
				'year_age.numeric' => 'حقل عدد السنين يجب ان يكون رقم فقط.',
				'year_age.required_without_all' => 'حقل عدد السنين يجب أن يكون أكبر من 0 فى حالة عدم وجود عدد أيام أو عدد أشهر',
				'ticket_num.required' => 'هذا الحقل مطلوب الأدخال.',
				'ticket_num.numeric' => 'حقل رقم التذكرة يجب أن يكون رقم فقط .',
				'ticket_num.unique' => 'رقم التذكرة موجود من قبل .',
				'ticket_type.required' => 'هذا الحقل مطلوب الأدخال.',
				'entry.required' => 'هذا الحقل مطلوب الأدخال.',
				'medical_id.required' => 'هذا الحقل مطلوب الأدخال.',
				'reservation_type.required' => 'هذا الحقل مطلوب الأدخال.',
				'sent_by_person.required' => 'هذا الحقل مطلوب الأدخال.',
				'ticket_companion_name.required_with' => 'هذا الحقل مطلوب فى حال وجود رقم البطاقة',
				'ticket_companion_sin.sin_format' => 'رقم البطاقة غير صحيح.',
				'ticket_companion_sin.different' => 'رقم البطاقة يجب ان يكون مختلف عن رقم بطاقة المريض',
			];
			if($input['ticket_status'] == "T")
				$clinic_ticket=Visit::where('ticket_number',$input['ticket_num'])
														->whereNull('ticket_type')
														->first();

			$constraints['fname'] = 'required|alpha|min:2|max:20';
			$constraints['sname'] = 'required|alpha|min:2|max:20';
			$constraints['mname'] = 'required|alpha|min:2|max:20';
			$constraints['lname'] = 'alpha|min:2|max:20';
			$constraints['serial_number'] = 'required';
			$constraints['reg_date'] = 'required|date';
			$constraints['reg_time'] = 'required';
			$constraints['gender'] = 'required';
			$constraints['address'] = 'required';
			$constraints['job'] = 'required';
			$constraints['sid'] = 'sin_format|unique:patients,sid';
			if($input['sid'] == "")
				$constraints['year_age'] = 'numeric|required_without_all:month_age,day_age';
			/* in case of ticket status ("T"=> number, "F"=> free word) is T, user types ticket numbar 
			and the oposite condition is F, user types free word */ 
			if($input['ticket_status'] == "T"){
				if(count($clinic_ticket) > 0)
					$constraints['ticket_num']='required|numeric|unique:visits,ticket_number,'.$clinic_ticket->id;
				else
					$constraints['ticket_num']='required|numeric|unique:visits,ticket_number';
			}
			$constraints['ticket_type']='required';
			$constraints['entry']='required';
			$constraints['medical_id']='required_if:all_deps,false';
			$constraints['reservation_type']='required';
			$constraints['sent_by_person']='required';
			$constraints['ticket_companion_name']='required_with:ticket_companion_sin';
			$constraints['ticket_companion_sin']='sin_format|different:sid';

			if($input['reservation_type'] == "T&E"){

				$messages['c_name.required_with'] = 'هذا الحقل مطلوب فى حال وجود رقم البطاقة';
				$messages['c_name.min']= 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['c_name.max']= 'هذا الحقل يجب الأ يتعدي :max حرف';
				$messages['c_address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['c_job.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['c_sid.different'] = 'حقل رقم بطاقة المرافق يجب أن يكون مختلف عن رقم بطاقة المريض.';
				$messages['c_sid.sin_format'] = 'رقم البطاقة غير صحيح.';
				$messages['file_number.required'] = 'هذا الحقل مطلوب الأدخال.';
				$messages['file_number.unique'] = 'هذا الرقم موجود من قبل';
				$messages['file_type.required'] = 'هذا الحقل مطلوب الأدخال';
				$messages['entry_date.date'] = 'هذا الحقل يجب أن يكون تاريخ';
				$messages['entry_date.after_or_equal_date'] = 'تاريخ الدخول يجب أن يكون أكبر من تاريخ التسجيل.';
				
				$constraints['entry_date']='date|after_or_equal_date:reg_date';
				$constraints['c_name']='required_with:c_sid|min:2|max:50';
				$constraints['c_address']='min:2';
				$constraints['c_job']='min:2';
				$constraints['c_sid']='sin_format|different:sid';
				$constraints['file_number']='required|unique:visits,file_number';
				$constraints['file_type']='required';
			}
			$this->validate($request, $constraints ,$messages);

			$input['name']=$input['fname']." ".$input['sname']." ".$input['mname']." ".$input['lname'];
			unset($input['fname']);unset($input['sname']);unset($input['mname']);unset($input['lname']);

			if($input['sid'] == "")
				$birthdate=return_birthdate($input['day_age'],$input['month_age'],$input['year_age']);
			else
				$birthdate=return_birthdate($input['sid']);
			
			$patient_input=
			array(
				'sid'=>$input['sid']==""?null:$input['sid'],
				'name'=>$input['name'],
				'gender'=>$input['gender'],
				'address'=>$input['address'],
				'birthdate'=>$birthdate,
				'job'=>$input['job'],
			);
			
			DB::beginTransaction();

			try{
				$the_last_id=Patient::orderBy('id', 'desc')->first();
				$patient_input['id']=($the_last_id->id==null)?1:$the_last_id->id+1;
				$patient_object=Patient::create($patient_input);
				$pid=$patient_object->id;
				$visit_input_data=
				array(
					'patient_id'=>$pid,
					'serial_number'=>$input['serial_number'],
					'ticket_number'=>$input['ticket_status'] == "T"?$input['ticket_num']:"مجاني",
					'ticket_status'=>$input['ticket_status'],
					'ticket_type'=>$input['ticket_type'],
					'user_id'=>Auth::id(),
					'entry_id'=>$input['entry'],
					'registration_datetime'=>Carbon::parse($input['reg_date']." ".$input['reg_time']),
					'watching_status'=>$input['watching_status']==""?null:$input['watching_status'],
					'sent_by_person'=>$input['sent_by_person'],
					'ticket_companion_name'=>$input['ticket_companion_name']==""?null:$input['ticket_companion_name'],
					'ticket_companion_sin'=>$input['ticket_companion_sin']==""?null:$input['ticket_companion_sin']
				);
				if($input['reservation_type'] == "T&E"){

					
					$visit_input_data['c_name']=$input['c_name']==""?null:$input['c_name'];
					$visit_input_data['sid']=$input['c_sid']==""?null:$input['c_sid'];
					$visit_input_data['relation_id']=$input['relation_id']==""?null:$input['relation_id'];
					$visit_input_data['address']=$input['c_address']==""?null:$input['c_address'];
					$visit_input_data['job']=$input['c_job']==""?null:$input['c_job'];
					$visit_input_data['room_number']=$input['room_number']==""?null:$input['room_number'];
					$visit_input_data['file_number']=$input['file_number'];
					$visit_input_data['file_type']=$input['file_type'];
					$visit_input_data['cure_type_id']=$input['cure_type_id']==""?null:$input['cure_type_id'];
					$visit_input_data['contract']=$input['contract']==""?null:$input['contract'];
					$visit_input_data['converted_by_doctor']=$input['converted_by_doctor']==""?null:$input['converted_by_doctor'];
					$visit_input_data['reference_doctor_id']=$input['reference_doctor_id']==""?null:$input['reference_doctor_id'];
					
					$visit_input_data['entry_date']=$input['entry_date']==""?$input['reg_date']:$input['entry_date'];
					$visit_input_data['entry_time']=$input['entry_time']==""?$input['reg_time']:$input['entry_time'];
				}
				$visit=Visit::create($visit_input_data);
				if($input['reservation_type'] == "T&E"){
					// Get the attached department to chosen clinic
					$clinic_id= MedicalUnit::find($input['medical_id']);
					if(is_null($clinic_id->parent_department_id)){
							return redirect()->back()
											->withFlashMessage( "يجب أن يكون للعيادة القسم الخاص بها لكي يتم عملية دخول المريض")
											->withMessageType("false")
											->withInput();
					}
					$visit->medicalunits()->attach($clinic_id->parent_department_id);
				}
				else{
					if(isset($input['all_deps'])){
						$visit->all_deps=true;
						$visit->save();
						$all_clinics=MedicalUnit::where('type','c')->lists('id');
						$all_clinics=$all_clinics->toArray();
						$visit->medicalunits()->attach($all_clinics);
						
					}
					else{
						$visit->medicalunits()->attach($input['medical_id']);
					}
				}
				
				DB::commit();
				$request->session()->flash('flash_message', "تم التسجيل بنجاح <br> كود المريض : $pid <br> تذكرة المريض : $visit_input_data[ticket_number]");
						
				$request->session()->flash('vid',$visit->id);
			}
			catch(\Exception $e){
				DB::rollback();
				dd($e);
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "حدثت مشكلة فى أدخال البيانات! حاول مرة أخرى");
			}
		}
		return redirect()->action('PatientController@indexDesk',array('pid'=>-1));

	}

	public function show(Request $request)
	{

		$patients= Patient::orderBy('id', 'desc')->select('id','name','gender','sid','birthdate','created_at','address')->take(100)->get();
		$user=User::find(Auth::id());
		$role_name=$user->role->name;
		$s_active = 'active';
		$table_header = 'بيانات المرضي المضافة حديثا';
		$data=$patients;
		if($request->is("admin/show")){
			$url = url("admin/show");
		}
		else{
			$url = url("patients/show");
		}
		return view('show_patients',compact('s_active','role_name','table_header','data','url'));
	}
	public function search(Request $request)
	{
		// Restore session after it expired and user return to log in
	
		$input = $request->all();
		$data= Patient::where(function($query) use ($input){
				if($input['name'] != "")
					$query->where('name','like',"%$input[name]%");
				if($input['code'] != "")
					$query->where('id','=',"$input[code]");
				if($input['sin'] != "")
					$query->where('sid','=',"$input[sin]");
				if($input['birthdate'] != "")
					$query->where('birthdate','like',"$input[birthdate]");
		})->orderBy('created_at', 'desc')->select('id','name','gender','sid','birthdate','created_at','address')->get();

		$user=User::find(Auth::id());
		$role_name=$user->role->name;
		$s_active = 'active';
		$table_header = 'بيانات المرضي حسب نتيجة البحث';
		$url = url("patients/show");
		return view('show_patients',compact('s_active','role_name','table_header','data','url'));
	}
	public function addvisit($id,$visit_id){
		// Restore session after it expired and user return to log in
		$patient_visit=array();
		$user_id=Auth::id();
		if($visit_id==-1)
			$patient_visit[0]= Patient::find($id);
		else
		$patient_visit = Visit::join('medical_unit_visit', 'visits.id', '=', 'medical_unit_visit.visit_id')
				  ->join('medical_units','medical_unit_visit.medical_unit_id','=','medical_units.id')
				  ->join('patients','patients.id','=','visits.patient_id')
				  ->leftJoin('users','users.id','=','visits.reference_doctor_id')
				  ->where('visits.id','=',$visit_id)
				  ->where('patients.id','=',$id)
				  ->where('type','=','d')
				  ->select('patients.id as id','visits.id as vid','patients.name as name','patients.address as paddress','patients.job as pjob','patients.birthdate','patients.sid','medical_units.id as dept_id','c_name','visits.sid as c_sid','relation_id','visits.address as vaddress','visits.job as vjob','visits.phone_num as vphone','entry_id',
				  'entry_time','entry_date','reference_doctor_id','room_number','file_number','contract','cure_type_id','file_type'
				  ,'converted_by_doctor')
				  ->get();
		//dd($patient_visit);
		$relations=Relation::lists('name', 'id');
		$medicalunits=MedicalUnit::where('type','=','d')->lists('name', 'id');
		$user=User::find(Auth::id());
		$entrypoints=$user->entrypoints()->select('name','id')->get();
		$entrypoint_Array=array();
		foreach($user->entrypoints as $row)
			$entrypoint_Array[$row->id]=$row->name;
		if($visit_id==-1){
			$medical_unit= MedicalUnit::where('type','=','d')->first();
			$first_department_doctors=$medical_unit->users()->lists('users.name','users.id');
		}
		else{
			$medical_unit= MedicalUnit::find($patient_visit[0]->dept_id);
			$first_department_doctors=$medical_unit->users()->lists('users.name','users.id');
		}
		$cure_types=CureType::lists('name','id');
		$file_types=FileType::lists('name','id');
		$sub_type_entrypoint=$user->entrypoints()->first()->sub_type;

		return view('addvisits',array('s_active'=>'active','data'=>$patient_visit,'relations'=>$relations,'medicalunits'=>$medicalunits,'entrypoints'=>$entrypoint_Array,'first_department_doctors'=>$first_department_doctors,'cure_types'=>$cure_types,'file_types'=>$file_types,'sub_type_entrypoint'=>$sub_type_entrypoint));
	}
	public function storeVisit(Request $request,$pid,$visit_id)
	{
		$input=$request->all();
		// Common constraints and their displayed messages
		$messages['sid.sin_format'] = 'رقم البطاقة غير صحيح';
		$messages['sid.different'] = 'حقل رقم بطاقة المرافق يجب أن يكون مختلف عن رقم بطاقة المريض';
		$messages['c_name.required'] = 'هذا الحقل مطلوب الأدخال';
		$messages['c_name.min'] ='هذا الحقل يجب الأ يقل عن :min حروف';
		$messages['c_name.max'] = 'هذا الحقل يجب الأ يتعدي :max حرف';
		$messages['address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
		$messages['job.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
		$messages['phone_num.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
		$messages['phone_num.max'] = 'هذا الحقل يجب الأ يتعدي :max حرف';


		$constraints['sid']='sin_format|different:patient_sid';
		$constraints['c_name']='required|min:2|max:50';
		$constraints['address']='min:3';
		$constraints['job']='min:3';
		$constraints['phone_num']='min:4|max:20';

		if($input['v_code']==""){
			$messages['entry.required'] = 'هذا الحقل مطلوب الأدخال';
			$messages['medical_id.required'] = 'هذا الحقل مطلوب الأدخال';
			$messages['entry_date.required'] = 'هذا الحقل مطلوب الأدخال';
			$messages['entry_time.required'] = 'هذا الحقل مطلوب الأدخال';

			$constraints['entry']='required';
			$constraints['medical_id']='required';
			$constraints['entry_date']='required';
			$constraints['entry_time']='required';
		}

		else{
			$messages['entry_date.required'] = 'هذا الحقل مطلوب الأدخال';
			$messages['entry_time.required'] = 'هذا الحقل مطلوب الأدخال';

			$constraints['entry_date']='required';
			$constraints['entry_time']='required';
		}
		$this->validate($request,$constraints,$messages);
		$input['patient_id']=$input['code'];
		// This condition for updating inpatient visit data which patient has already existed
		// Else we will insert new visit data with current patient data
		if($input['v_code']!=""){
			DB::beginTransaction();
			try{
				$visit=Visit::find($input['v_code']);

				$visit->c_name=$input['c_name']==""?null:$input['c_name'];$visit->sid=$input['sid']==""?null:$input['sid'];$visit->relation_id=$input['relation_id']==""?null:$input['relation_id'];
				$visit->address=$input['address']==""?null:$input['address'];$visit->job=$input['job']==""?null:$input['job'];$visit->phone_num=$input['phone_num']==""?null:$input['phone_num'];
				$visit->converted_by_doctor=$input['converted_by_doctor']==""?null:$input['converted_by_doctor'];
				$visit->room_number=$input['room_number']==""?null:$input['room_number'];
				$visit->file_number=$input['file_number'];
				$visit->file_type=$input['file_type'];
				$visit->cure_type_id=$input['cure_type_id']==""?null:$input['cure_type_id'];
				$visit->contract=$input['contract']==""?null:$input['contract'];
				$visit->reference_doctor_id=!isset($input['reference_doctor_id'])?null:$input['reference_doctor_id'];
				if($visit->exit_date == null){
						$visit->entry_date=$input['entry_date'];
				}
				else{
					$diff=Carbon::parse($input['entry_date'])->diff(Carbon::parse($visit->exit_date));
					if($diff->invert == 1){
						return redirect()->back()
									 ->withErrors(['entry_date' => ' لا يمكن أن يكون تاريخ الدخول أكبر من تاريخ تسجيل خروج المريض '])
									 ->withInput();
					}
					$visit->entry_date=$input['entry_date'];
				}
				$visit->entry_time=$input['entry_time'];
				$visit->save();
				$request->session()->flash('message_type', "true");
				$request->session()->flash('flash_message', "تم تحديث الملف بالنجاح");
				DB::commit();

			 }
			 catch(\Exception $e){
				 $request->session()->flash('message_type', "false");
				 $request->session()->flash('flash_message', "حدث خطأ حاول مرة اخرى");
				 DB::rollback();
			}
			return redirect()->action('PatientController@addvisit',array('id'=>$input['patient_id'],'visit_id'=>$input['v_code']));
		}
		else{
			// check if a patient is already exist ( visit closed flag is false ) in the same or another clinic where he/she reserve.
			if($input['patient_id'] != "" && $this->checkIfPatientIsExistTodayInClinic($input['patient_id']) > 0)
			{
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "هذا المريض موجود فى عيادة و لم يتم إنهاء زيارته");
			}
			// check if a patient is already exist in a department.
			else if($input['patient_id'] != "" && $this->checkIfPatientIsExistInDepartment($input['patient_id']) > 0)
			{
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "هذا المريض موجود فى القسم الداخلي و لم يتم اخراجه");

			}
			else{
				$visit=new Visit;
				$visit->user_id=Auth::id();
				$visit->entry_id=$input['entry']; $visit->patient_id=$input['patient_id'];
				$visit->c_name=$input['c_name']==""?null:$input['c_name'];$visit->sid=$input['sid']==""?null:$input['sid'];$visit->relation_id=$input['relation_id']==""?null:$input['relation_id'];
				$visit->address=$input['address']==""?null:$input['address'];$visit->job=$input['job']==""?null:$input['job'];$visit->phone_num=$input['phone_num']==""?null:$input['phone_num'];
				$visit->entry_date=$input['entry_date']; $visit->entry_time=$input['entry_time'];
				$visit->converted_by_doctor=$input['converted_by_doctor']==""?null:$input['converted_by_doctor'];
				$visit->room_number=$input['room_number']==""?null:$input['room_number'];
				$visit->file_number=$input['file_number'];
				$visit->file_type=$input['file_type'];
				$visit->cure_type_id=$input['cure_type_id']==""?null:$input['cure_type_id'];
				$visit->contract=$input['contract']==""?null:$input['contract'];
				$visit->reference_doctor_id=$input['reference_doctor_id']==""?null:$input['reference_doctor_id'];
				$visit_object=Visit::create($input);

				$visit_object->medicalunits()->attach($input['medical_id']);
				$request->session()->flash('flash_message', "تم التسجيل بالنجاح");
				$request->session()->flash('vid', $visit_object->id);
				$request->session()->flash('id', $input['patient_id']);
			}
			return redirect()->action('PatientController@addvisit',array('id'=>$input['patient_id'],'visit_id'=>-1));
		}

	}
	// check if a patient is already exist ( visit closed flag is false ) in the same or another clinic where he/she reserve.
	public function checkIfPatientIsExistTodayInClinic($pid,$mid='',$all_deps=false,$reg_date='')
	{
		if($mid=="")
			$patient_visits=Patient::find($pid)
									 ->visits()
									 ->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
									 ->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
									 ->where('type','c')
									 ->whereDate('visits.created_at','=',date('Y-m-d',time()))
									 ->where('closed',false)
									 ->where('cancelled',false)
									 ->count();
		else
			$patient_visits=Patient::find($pid)
									 ->visits()
									 ->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
									 ->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
									 ->where('type','c')
									 ->where(function($query) use($mid,$all_deps,$reg_date){
										if(!$all_deps){
											$query->where('medical_units.id',$mid);
											if($reg_date=='')
												$query->whereDate('visits.created_at','=',date('Y-m-d',time()));
											else
												$query->whereDate('visits.registration_datetime','=',$reg_date);
										}
										else{
											$query->whereDate('visits.registration_datetime','=',$reg_date);
										}
											
									 })
									 ->where('closed',false)
									 ->where('cancelled',false)
									 ->count();
		if($patient_visits > 0)
			return true;
		return false;
	}
	// check if a patient is already exist in a department.
	public function checkIfPatientIsExistInDepartment($pid)
	{
		$patient_visits=Patient::find($pid)
							   ->visits()
							   ->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
							   ->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
							   ->where('type','d')
							   ->where('closed',false)
							   ->count();
		if($patient_visits > 0)
			return true;
		return false;
	}
	public function showvisits($eid,Request $request)
	{
		$user_row=User::find(Auth::id());
		$role_name=$user_row->role->name;
		$medical_units=MedicalUnit::lists('name', 'id');
		$desks=Entrypoint::where('type',3)->get();
		$header="بيانات حجز كشف المرضي المضافة حديثا خلال شهر";
		$medical_type="c";
		$sub_type_entrypoint=$user_row->entrypoints()->first()->sub_type;
		$visits = Visit::with(array('patient','medicalunits'=>function($query){
							$query->orderBy('pivot_created_at','asc');	
						}))
						->whereHas('medicalunits',function($query) use($medical_type){
							$query->where('type',$medical_type);
						})
						->where('cancelled',false)
						->where(function($query) use($eid){
							$query->where('entry_id',$eid)
								->orWhere('convert_to_entry_id',$eid);
						})
						->whereBetween('visits.created_at',[Carbon::today()->subMonth()->format('Y-m-d'),Carbon::tomorrow()->format('Y-m-d')])
						->orderBy('id','desc')
						->orderBy('created_at','desc')
						->get();
		return view('show_visits',array('sv_active'=>'active','table_header'=>$header,'data'=>$visits,'medicalunits'=>$medical_units,'role_name'=>$role_name,'desks'=>$desks,'sub_type_entrypoint'=>$sub_type_entrypoint));
	}

	public function showinpatientvisits()
	{
		$user=User::find(Auth::id());
		$table_header="بيانات مرضي الدخول المضافة حديثا";
		$medical_type="d";
		$ip_active='active';
		$role_name=$user->role->name;
		$sub_type_entrypoint=$user->entrypoints()->first()->sub_type;
		// This condition for reception who inserting inpatient data with his/her ticket in clinic reservation
		if($role_name != 'Entrypoint')
			$ticket_and_entry='true';
		$data= Visit::with(array('cure_type','patient','medicalunits'=>function($query){
							$query->orderBy('pivot_created_at', 'desc');
						}))
						->whereHas('medicalunits',function($query) use($medical_type){
							$query->where('type',$medical_type);
						})
						->where('cancelled',false)
						->where(function($query) use($role_name){
							if($role_name == "Receiption")
								$query->where('user_id',Auth::id());
						})
						->orderBy('id', 'desc')
						->take(100)->get();			
		return view('show_visits',compact('ip_active','table_header','data','role_name','ticket_and_entry','sub_type_entrypoint'));
	}

	public function exitvisit($id,$visit_id)
	{
		$patient_data=Visit::join('medical_unit_visit', 'visits.id', '=', 'medical_unit_visit.visit_id')
				  ->join('medical_units','medical_unit_visit.medical_unit_id','=','medical_units.id')
				  ->join('patients','patients.id','=','visits.patient_id')
				  ->leftJoin('users','users.id','=','visits.reference_doctor_id')
				  ->where('visits.id','=',$visit_id)
				  ->where('patients.id','=',$id)
				  ->where('type','=','d')
				  ->select('patients.id as id','patients.sid as sid','visits.id as vid','patients.name as name','medical_units.name as mname','users.name as uname','entry_date','exit_date','final_diagnosis','exit_status','doctor_recommendation')
				  ->get();
				 // dd($visit_id);
		$user = User::find(Auth::id());
		$sub_type_entrypoint=$user->entrypoints()->first()->sub_type;
		return view('exit_patient',array('s_active'=>'active','data'=>$patient_data,'sub_type_entrypoint'=>$sub_type_entrypoint));
	}

	public function storeexitvisit($id,$visit_id,Request $request)
	{
		$input=$request->all();
		$messages['final_diagnosis.required'] = 'هذا الحقل مطلوب الأدخال';
		$messages['exit_condition.required'] = 'هذا الحقل مطلوب الأدخال';
		$messages['exit_time.required'] ='هذا الحقل مطلوب الأدخال';
		$messages['exit_time.date'] = 'هذا الحقل يجب أن يكون تاريخ فقط';
		$messages['exit_time.after'] = 'حقل تاريخ الخروج يجب أن يكون أكبر من تاريخ الدخول';

		$constraints['final_diagnosis']='required';
		$constraints['exit_condition']='required';
		$constraints['exit_time']='required|date';

		$this->validate($request,$constraints,$messages);

		$visit_data=Visit::find($visit_id);

		$diff=Carbon::parse($visit_data->entry_date)->diff(Carbon::parse($input['exit_time']));

		if($diff->invert == 1){
			return redirect()->back()
						 ->withErrors(['exit_time' => ' لا يمكن أن يكون تاريخ الخروج أقل من تاريخ دخول المريض '])
						 ->withInput();
		}
		$visit_data->exit_status=$input['exit_condition'];
		$visit_data->exit_date=$input['exit_time'];
		$visit_data->final_diagnosis=$input['final_diagnosis'];
		if(isset($input['doctor_recommendation']))
			$visit_data->doctor_recommendation=$input['doctor_recommendation'];
		$visit_data->closed=1;
		DB::beginTransaction();
		$visit_data->save();
		DB::commit();
		return redirect()->back()->withFlashMessage('تمت العملية بالنجاح ');
	}
	public function checkexistSID(Request $request){
		$patients= Patient::where('sid',$request->get('sid'))->select('id','name','gender','birthdate','address','job','social_status','phone_num')->get();
		if(count($patients) > 0){
			if($request->get('checkflag') == "true")
				return response()->json(['success' => 'false','code'=>$patients[0]['id']]);
			else
				return response()->json(['success' => 'true','data'=>$patients]);
		}
		else{
			if($request->get('checkflag') == "true")
				return response()->json(['success' => 'true']);
			else
				return response()->json(['success' => 'false']);
		}
	}
	public function checkexistPID(Request $request){
		$patients= Patient::where('id',$request->get('pid'))->select('id','sid','name','birthdate','gender','address','job','social_status','phone_num')->get();
		if(count($patients) > 0)
			return response()->json(['success' => 'true','data'=>$patients]);
		else
			return response()->json(['success' => 'false']);
	}
	public function checkExistName(Request $request){
		$patients= Patient::where('name','like','%'.$request->get('name').'%')
						 ->select('id','name','address','birthdate')
						 ->get();
		if(count($patients) > 0)
			return response()->json(['success' => 'true','patients'=>$patients]);
		else
			return response()->json(['success' => 'false']);
	}
	public function ajax_get_department_doctors(Request $request){

		$department_doctors=MedicalUnit::find($request->get('mid'))->users()->select('users.name','users.id')->get();
		if(count($department_doctors) > 0)
			return response()->json(['success' => 'true','data'=>$department_doctors]);
		else
			return response()->json(['success' => 'false']);
	}
	public function ajax_get_patient_data(Request $request){

		$patient_data=Patient::where('id',$request->get('pid'))->select('name','gender','sid','address','birthdate','phone_num','social_status','job')->get();
		if(count($patient_data) > 0)
			return response()->json(['success' => 'true','data'=>$patient_data]);
		else
			return response()->json(['success' => 'false']);
	}
}
