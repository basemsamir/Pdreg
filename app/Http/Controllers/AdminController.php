<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Visit;
use App\MedicalUnit;
use App\Patient;
use App\Entrypoint;
use App\User;
use App\DataEntryPlaceType;
use Auth;
use DB;
use Session;
use Fish\Logger\Log;
use App\Role;
use Artisan;
use Validator;
use File;
use DateTime;
use Activity;
class AdminController extends Controller
{
    //
	public function index(Request $request){
		$outpatient_visits=$this->_getNumberVisits('c',date('Y-m-d'));
		$outpatient_visit_count=0;
		if(isset($outpatient_visits[0])){
			foreach($outpatient_visits as $visits)
				$outpatient_visit_count+=$visits->numberOfVisits;

		}
		else
			$outpatient_visits=0;
		$inpatient_visits=Visit::numberofinpatients();
		$patients_count=Patient::numberofpatientstoday()->count();
		$user_ids=User::whereNotIn('role_id',[1,6])->get(['id']);
		$role_name='Admin';
		$d_active='active';
		$user = new User;
		$loggable_number=Activity::users(60)->groupBy('user_id')->count();
		$active_users= Activity::users(60)->groupBy('user_id')->get();
		$visits= Visit::with('patient')->orderBy('id','desc')->take(10)->get();
		return view('home',compact('d_active','role_name','patients_count','inpatient_visits','outpatient_visit_count','loggable_number','active_users','visits'));
	}
	public function indexUser(Request $request){
		$roles=Role::whereNotIn('id',[1,3])->lists('arabic_name','id');
		$users=User::join('roles','roles.id','=','users.role_id')
					->whereNotIn('roles.id',[1,3])
					->where('users.id','!=',Auth::id())
					->select('users.id','users.name as username','email','roles.arabic_name as rolename')->get();
		return view('add_user',array('iu_active'=>'active','roles'=>$roles,'users'=>$users));
	}
	public function storeUser(Request $request)
	{
		$input=$request->all();
		if($input['uid'] == ""){
			$messages = [
				'name.required' => 'هذا الحقل مطلوب الأدخال.',
				'name.min' => 'هذا الحقل يجب الأ يقل عن :min حروف',
				'name.max' => 'هذا الحقل يجب الأ يتعدي :max حرف',
				'email.required' =>  'هذا الحقل مطلوب الأدخال.',
				'email.email' => 'هذا الحقل يجب ان يكون فى بريد الألكتروني',
				'email.unique' => 'هذا البريد موجود من قبل',
				'password.required' =>  'هذا الحقل مطلوب الأدخال.',
				'password.min' => 'هذا الحقل يجب الأ يقل عن :min حروف',
				'password.max' => 'هذا الحقل يجب الأ يتعدي :max حرف',
				'cpassword.required_with' =>  'هذا الحقل مطلوب الأدخال لتأكيد كلمة المرور',
				'cpassword.same' => 'كلمتي المرور غير متطابقتين',
				'role.required' => 'هذا الحقل مطلوب الأدخال.',
			];
			$this->validate($request, [
				'name' => 'required|min:2|max:30',
				'email' => 'required|email|unique:users',
				'password' => 'required|min:6|max:20',
				'cpassword' => 'required_with:password|same:password',
				'role' => 'required',
			],$messages);
		}
		else{
			$messages = [
				'name.required' => 'هذا الحقل مطلوب الأدخال.',
				'name.min' => 'هذا الحقل يجب الأ يقل عن :min حروف',
				'name.max' => 'هذا الحقل يجب الأ يتعدي :max حرف',
				'email.required' =>  'هذا الحقل مطلوب الأدخال.',
				'email.email' => 'هذا الحقل يجب ان يكون فى بريد الألكتروني',
				'email.unique' => 'هذا البريد موجود من قبل',
				'password.required' =>  'هذا الحقل مطلوب الأدخال.',
				'password.min' => 'هذا الحقل يجب الأ يقل عن :min حروف',
				'password.max' => 'هذا الحقل يجب الأ يتعدي :max حرف',
				'cpassword.required_with' =>  'هذا الحقل مطلوب الأدخال لتأكيد كلمة المرور',
				'cpassword.same' => 'كلمتي المرور غير متطابقتين',
				'role.required' => 'هذا الحقل مطلوب الأدخال.',
			];
			$this->validate($request, [
				'name' => 'required|min:2|max:30',
				'email' => 'required|email|unique:users,email,'.$input['uid'],
				'password' => 'min:6|max:20',
				'cpassword' => 'required_with:password|same:password',
				'role' => 'required',
			],$messages);
		}
		if($input['uid'] == "")
		{
			unset($input['cpassword']);
			$input['role_id']=$input['role'];
			$input['password']=bcrypt($input['password']);
			unset($input['role']);

			User::create($input);
			$request->session()->flash('flash_message', "تم التسجيل بنجاح");
		}
		else{
			$user=User::find($input['uid']);
			// Check if this user is a doctor
			$role=$user->role->name;
			switch($role){
				case 'Doctor':
					$medicalunits=$user->medicalunits()->get();
					if(count($medicalunits) > 0){
						if($user->role->id != $input['role']){
							$request->session()->flash('message_type', "false");
							$request->session()->flash('flash_message', "لا يمكن تعديل دور هذا الطبيب بسبب ارتباطه بالقسم الخاص به");
							return redirect()->action('AdminController@indexUser');
						}
					}
					break;
				case 'Entrypoint':
				case 'Receiption':
				case 'Desk':
					$entrypoints=$user->entrypoints()->get();
					if(count($entrypoints) > 0){
						if($user->role->id != $input['role']){
							$request->session()->flash('message_type', "false");
							if($role=="Entrypoint")
								$request->session()->flash('flash_message', "لا يمكن تعديل دور هذا المستخدم بسبب ارتباطه بالمكتب دخول الخاص به");
							elseif($role=="Receiption")
								$request->session()->flash('flash_message', "لا يمكن تعديل دور هذا المستخدم بسبب ارتباطه بالمكتب حجز التذاكر الخاص به");
							else
								$request->session()->flash('flash_message', "لا يمكن تعديل دور هذا المستخدم بسبب ارتباطه بالمكتب  الاستقبال الخاص به");
							return redirect()->action('AdminController@indexUser');
						}
					}
					break;

			}
			$user->name=$input['name'];
			$user->email=$input['email'];
			if($input['password'] != "")
				$user->password=bcrypt($input['password']);
			$user->role_id=$input['role'];
			$user->save();
			$request->session()->flash('flash_message', "تم التعديل بنجاح");
		}
		return redirect()->action('AdminController@indexUser');
	}
	public function destroyUser($uid)
	{
		$user = User::find($uid);
		$user->delete();
		Session::flash('flash_message', "تم الحذف بنجاح");
		return redirect()->back();
	}
	public function indexMedicalUnit(Request $request){

		$medical_units=MedicalUnit::select('name','id','type')->get();
		return view('medicalunits',array('mu_active'=>'active','o'=>'1','data'=>$medical_units));
	}
	public function storeMedicalUnit(Request $request)
	{
		$input=$request->all();
		$messages = [
			'name.required' => 'هذا الحقل مطلوب الأدخال.',
			'name.min' => 'هذا الحقل يجب الأ يقل عن :min حروف',
			'name.max' => 'هذا الحقل يجب الأ يتعدي :max حرف',
			'type.required' => 'هذا الحقل مطلوب الأدخال.',
		];

		$rules['name']='required|min:2|max:30';
		$rules['type']='required';

		$this->validate($request, $rules ,$messages);
		if($input['mid']==""){
			MedicalUnit::create($input);
			$request->session()->flash('flash_message', "تم التسجيل بنجاح");
		}
		else{
			//dd($input);

			$medical_unit=MedicalUnit::find($input['mid']);
			if($medical_unit->users()->count() > 0 && $medical_unit->type != $input['type'])
			{
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "لا يمكن تعديل نوع الوحدة الطبية لأرتباطها بالعدد من الأطباء");
				return redirect()->action('AdminController@indexMedicalUnit');
			}
			$medical_unit->name=$input['name'];
			$medical_unit->type=$input['type'];
			$medical_unit->save();
			$request->session()->flash('flash_message', "تم التعديل بنجاح");
		}

		return redirect()->action('AdminController@indexMedicalUnit');
	}

	public function indexDoctorToDepartment(Request $request){


		$departments=MedicalUnit::where('type','d')->lists('name','id');
		$doctor_departments=User::join('medical_unit_user','medical_unit_user.user_id','=','users.id')
								->join('medical_units','medical_unit_user.medical_unit_id','=','medical_units.id')
								->select('users.name as username','medical_units.name as medicalname','users.id as user_id','medical_units.id as dep_id','email')
								->where('type','d')
								->where('users.deleted_at',null)
								->orderBy('medical_units.id')
								->get();
		$doctors=User::where('role_id',2)->lists('name','id');
		return view('adddoctortodepartment',array('mu_active'=>'active','o'=>3,'departments'=>$departments,'doctors'=>$doctors,'doctor_departments'=>$doctor_departments));
	}
	public function storeDoctorToDepartment(Request $request)
	{
		$input=$request->all();
		$messages = [
			'doc_name.required' => 'هذا الحقل مطلوب الأدخال.',
			'department.required' => 'هذا الحقل مطلوب الأدخال.',
		];
		$this->validate($request, [
			'doc_name' => 'required',
			'department' => 'required',
		],$messages);

		$doctor=User::find($input['doc_name']);
		$department=MedicalUnit::find($input['department']);
		if(count($doctor->medicalunits()->where('medical_unit_id',$input['department'])->get()) == 0){

			$clinics_department=MedicalUnit::where('parent_department_id',$input['department'])->get();
			DB::beginTransaction();
			try{
				$doctor->medicalunits()->attach($input['department']);
				foreach($clinics_department as $c){
					$doctor->medicalunits()->attach($c->id);
				}
				$request->session()->flash('message_type', "true");
				$request->session()->flash('flash_message', "تم الأضافة بنجاح");
				DB::commit();
			}
			catch (\Exception $e){
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "يوجد مشكلة فى الخدمة حاول مرة اخري");
				DB::rollBack();
			}
		}
		else{
			$request->session()->flash('message_type', "false");
			$request->session()->flash('flash_message', "هذا الطبيب مضاف الي  هذا القسم سابقا");
		}

		return redirect()->action('AdminController@indexDoctorToDepartment');
	}
	public function detachDoctorToDepartment($user_id,$dep_id)
	{
		$user = User::find($user_id);
		DB::beginTransaction();
		try{
			$user->medicalunits()->detach($dep_id);
			$clinics_department=MedicalUnit::where('parent_department_id',$dep_id)->get();
			foreach($clinics_department as $c){
				$user->medicalunits()->detach($c->id);
			}
			DB::commit();
			Session::flash('flash_message', "تم الألغاء بنجاح");
		}
		catch(\Exception $e){
			Db::rollBack();
			Session::flash('flash_message', "حدثت مشكلة حاول مرة أخري");
		}
		return redirect()->back();
	}
	public function indexClinicToDepartment(Request $request){

		$clinics=MedicalUnit::where('type','c')->lists('name','id');
		$departments=MedicalUnit::where('type','d')->lists('name','id');
		$c=DB::table('medical_units as d')
			 ->join('medical_units as c','c.parent_department_id','=','d.id')
			 ->select(DB::raw('d.name as department, c.name as clinic'))
			 ->orderBy('d.id')
			 ->get();
		return view('addclinictodepartment',array('mu_active'=>'active','o'=>2,'clinics'=>$clinics,'departments'=>$departments,'clinic_departments'=>$c));
	}
	public function storeClinicToDepartment(Request $request)
	{
		$input=$request->all();
		$messages = [
			'clinic.required' => 'هذا الحقل مطلوب الأدخال.',
			'department.required' => 'هذا الحقل مطلوب الأدخال.',
		];
		$this->validate($request, [
			'clinic' => 'required',
			'department' => 'required',
		],$messages);

		DB::beginTransaction();
		try{
			$clinic=MedicalUnit::find($input['clinic']);
			if($clinic->parent_department_id == null){
				$clinic->parent_department_id=$input['department'];
				$clinic->save();

				$doctors=User::join('medical_unit_user','medical_unit_user.user_id','=','users.id')
							 ->where('medical_unit_id',$input['department'])
							 ->select('users.id')
							 ->get();

				foreach($doctors as $row){
					$row=User::find($row['id']);
					$row->medicalunits()->attach($input['clinic']);
				}
				DB::commit();
				$request->session()->flash('message_type', "true");
				$request->session()->flash('flash_message', "تم الأضافة بنجاح");
			}
			else{
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "هذه العيادة تم أضافة القسم المرتبط بيها");
			}
		}
		catch(\Exception $e){
			DB::rollBack();
			$request->session()->flash('message_type', "false");
			$request->session()->flash('flash_message', "حدثت مشكلة حاول مرة اخري");
		}
		return redirect()->action('AdminController@indexClinicToDepartment');
	}

	public function indexEntrypoint(){
		$entrypoints=Entrypoint::with('data_entry_place_type')->select('name','id','location','type','sub_type')->get();
		$data_entry_place_types=DataEntryPlaceType::lists('name','id');
		return view('entrypoints',array('e_active'=>'active','o'=>1,'entrypoints'=>$entrypoints,'data_entry_place_types'=>$data_entry_place_types));
	}
	public function storeEntrypoint(Request $request)
	{
		$input=$request->all();
		$messages = [
			'name.required' => 'هذا الحقل مطلوب الأدخال.',
			'name.min' => 'هذا الحقل يجب الأ يقل عن :min حروف',
			'name.max' => 'هذا الحقل يجب الأ يتعدي :max حرف',
			'name.unique' => 'أسم المكتب موجود من قبل',
			'type.required' => 'هذا الحقل مطلوب الأدخال.',
			'location.required' => 'هذا الحقل مطلوب الأدخال.',
			'location.min' => 'هذا الحقل يجب الأ يقل عن :min حروف',
			'location.max' => 'هذا الحقل يجب الأ يتعدي :max حرف',
		];
		if($input['eid'] != "")
			$rules['name']="required|unique:entrypoints,name,$input[eid]|min:2|max:30";
		else
			$rules['name']='required|unique:entrypoints,name|min:2|max:30';
		$rules['type']='required';
		$rules['location']='required|min:2|max:250';
		$this->validate($request,$rules,$messages);
		if($input['eid']==""){
			if(isset($input['r1']) && $input['type'] == 2){
				$input['sub_type']=$input['r1'];
			}
			Entrypoint::create($input);
			$request->session()->flash('flash_message', "تم التسجيل بنجاح");
		}
		else{
			//dd($input);

			$entrypoint=Entrypoint::find($input['eid']);

			if($entrypoint->users()->count() > 0 && $entrypoint->type != $input['type']) {
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "لا يمكن تعديل نوع المكتب لأرتباطه بالعدد من المستخدمين");
				return redirect()->action('AdminController@indexEntrypoint');
			}
			$entrypoint->name=$input['name'];
			$entrypoint->location=$input['location'];
			$entrypoint->type=$input['type'];
			if(isset($input['r1']) && $input['type'] == 2)
			{
				$entrypoint->sub_type=$input['r1'];
			}
			$entrypoint->save();
			$request->session()->flash('flash_message', "تم التعديل بنجاح");
		}

		return redirect()->action('AdminController@indexEntrypoint');
	}

	public function indexEmployeeToEntrypoint(Request $request){

		$entrypoints=Entrypoint::lists('name','id');
		$employees=User::whereIn('role_id',[4,5,7])->lists('name','id');
		$employee_users=Entrypoint::join('entrypoint_user','entrypoint_user.entrypoint_id','=','entrypoints.id')
								  ->join('users','users.id','=','entrypoint_user.user_Id')
								  ->select('users.name as username','entrypoints.name as entrypointname','entrypoints.id as entry_id','users.id as user_id','email')
								  ->where('users.deleted_at',null)
								  ->get();
		return view('addemployeetoentrypoint',array('e_active'=>'active','o'=>2,'entrypoints'=>$entrypoints,'employees'=>$employees,'employee_users'=>$employee_users));
	}
	public function storeEmployeeToEntrypoint(Request $request)
	{
		$input=$request->all();
		$messages = [
			'emp_name.required' => 'هذا الحقل مطلوب الأدخال.',
			'entrypoint.required' => 'هذا الحقل مطلوب الأدخال.',
		];
		$this->validate($request, [
			'emp_name' => 'required',
			'entrypoint' => 'required',
		],$messages);

		$user=User::find($input['emp_name']);

		$entrypoint=Entrypoint::find($input['entrypoint']);
		$role_name=$user->role->name;
		// 2 represent Entrypoint in data_entry_place_type table
		if($entrypoint->type==2){
			if($role_name=='Entrypoint'){
				$user->entrypoints()->attach($input['entrypoint']);
				$request->session()->flash('message_type', "true");
				$request->session()->flash('flash_message', "تم الأضافة بنجاح");
			}
			else{
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "هذا الموظف ليس بدور موظف مكتب دخول");
			}
		}
		elseif($entrypoint->type==3){
			if($role_name=='Desk'){
				$user->entrypoints()->attach($input['entrypoint']);
				$request->session()->flash('message_type', "true");
				$request->session()->flash('flash_message', "تم الأضافة بنجاح");
			}
			else{
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "هذا الموظف ليس بدور موظف مكتب استقبال");
			}
		}
		else{
			if($role_name=='Receiption'){
				$user->entrypoints()->attach($input['entrypoint']);
				$request->session()->flash('message_type', "true");
				$request->session()->flash('flash_message', "تم الأضافة بنجاح");
			}
			else{
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "هذا الموظف ليس بدور موظف حجز تذاكر العيادات");
			}
		}
		return redirect()->action('AdminController@indexEmployeeToEntrypoint');
	}

	public function detachEmployeeToEntrypoint($user_id,$entry_id)
	{
		$user = User::find($user_id);
		$user->entrypoints()->detach($entry_id);
		Session::flash('flash_message', "تم الألغاء بنجاح");
		return redirect()->back();
	}

	public function showLogData()
	{
		$logdata = Log::take(100)->orderBy('id','desc')->get();
		return view('show_log_data',array('ld_active'=>'active','ldata'=>$logdata));
	}
	public function searchLogData()
	{
		$input=request()->all();
		$logdata = Log::between($input['date'],date('Y-m-d',time()))->orderBy('id','desc')->get();
		return view('show_log_data',array('ld_active'=>'active','ldata'=>$logdata));
	}
	public function print_visits_today($eid){

		$current_user=User::find(Auth::id());
		if($current_user->role->name == "Doctor" || $current_user->role->name == "Nursing" || $current_user->role->name == "Entrypoint")
			return redirect()->guest('auth/login');
		$entrypoint=Entrypoint::find($eid);
		$current_user=User::find(Auth::id());
		if($current_user->role->id == 5 || $current_user->role->id == 7){
			$medical_type="c";
			$header="بيانات حجز كشف المرضي فى ".$entrypoint['name']." بتاريخ ".date('d-m-Y');$medical_type="c";
			$visits_user[0]= $this->_getVisits($medical_type,0,$entrypoint,$current_user->role->id,$current_user->id);
			$visits_count=count($visits_user[0]);

			return view('reports.visits_today',array('r1_active'=>'active','data'=>$visits_user,'table_header'=>$header,'medical_type'=>$medical_type,'numberOfVisits'=>$visits_count,'today_date'=>true,'role_name'=>$current_user->role->name));
		}

		else{
			$user=$entrypoint->users()->firstOrFail();

			$header=""; $medical_type="";

			if($user->role->id==4){
				$header=" بيانات المرضي الداخلي الذي تم تحويلهم اليوم ";$medical_type="d";
				$visits = $this->_getVisits($medical_type,0,$entrypoint,$user->role->id,null);
				return view('reports.visits_today',array('r1_active'=>'active','data'=>$visits,'table_header'=>$header,'medical_type'=>$medical_type));
			}
			elseif($user->role->id==5 || $user->role->id==7){
				$users=$entrypoint->users()->get();
				$header="بيانات حجز كشف المرضي فى ".$entrypoint['name']." بتاريخ ".date('d-m-Y');$medical_type="c";
				$visits_count=0;
				if(count($users) > 1){
					for($i=0;$i<count($users);$i++){
						$visits_user[$i] = $this->_getVisits($medical_type,0,$entrypoint,$user->role->id,$users[$i]->id);
						$visits_count+=count($visits_user[$i]);
					}
				}
				else{
					$visits_user[0]= $this->_getVisits($medical_type,0,$entrypoint,$user->role->id,null);
					$visits_count=count($visits_user[0]);
				}

				return view('reports.visits_today',array('r1_active'=>'active','data'=>$visits_user,'table_header'=>$header,'medical_type'=>$medical_type,'numberOfVisits'=>$visits_count,'today_date'=>true,'role_name'=>$user->role->name));
			}
		}
	}
	public function print_visits_period(){
		$this->flash_report_sessions();
		$reception_users=User::where('role_id',5)->lists('name','id');
		$r2_active='active';
		return view('report_visits',compact('r2_active','reception_users'));
	}
	public function show_visits_period(Request $request){

		$this->flash_report_sessions();
		$input=$request->all();
		if($input['submit'] == "reload"){
			return redirect()->action('AdminController@print_visits_period');
		}

		if(array_key_exists("determined_date",$input)){
			$messages = [
				'determined_date.required' => 'حقل تاريخ معين مطلوب الأدخال .',
				'determined_date.date' => 'حقل تاريخ معين يجب أن يكون تاريخ .'
			];
			$this->validate($request, [
				'determined_date' => 'required|date',
			],$messages);
		}
		else{
			$messages = [
				'fromdate.required' => 'حقل تاريخ من مطلوب الأدخال.',
				'fromdate.date' => 'تاريخ من يجب أن يكون تاريخ فقط.',
				'todate.required' => 'حقل تاريخ ألى مطلوب الأدخال.',
				'todate.date' => 'تاريخ ألى يجب أن يكون تاريخ فقط.',
				'todate.after' => ' حقل تاريخ ألى يجب أن يكون أكبر من التاريخ من .',
			];
			$this->validate($request, [
				'fromdate' => 'required|date',
				'todate' => 'required|date|after:fromdate',
			],$messages);
		}
		$reception_users=User::where('role_id',5)->lists('name','id');
		$input['reception_name']=isset($input['reception_name'])?$input['reception_name']:null;

		if(is_null($input['reception_name'])){
			$receiptionists=User::withTrashed()->where('role_id',5)->get();
			$request->session()->put('receiptionists',$receiptionists);
			$visits_count=0;
			for($i=0;$i<count($receiptionists);$i++){
				if(array_key_exists("determined_date",$input)){
					$visits_user[$i] = $this->_getVisits('c',1,'',5,$receiptionists[$i]->id,$input['determined_date']);
				}
				else {
					$visits_user[$i] = $this->_getVisits('c',1,'',5,$receiptionists[$i]->id,$input['fromdate'],$input['todate']);
				}
				$visits_count+=count($visits_user[$i]);
			}
		}
		else{
			$receiptionist=User::find($input['reception_name']);
			$request->session()->put('receiptionist',$receiptionist);
			if(array_key_exists("determined_date",$input)){
				$visits_user[0]= $this->_getVisits('c',1,'',5,$input['reception_name'],$input['determined_date']);
			}
			else {
				$visits_user[0]= $this->_getVisits('c',1,'',5,$input['reception_name'],$input['fromdate'],$input['todate']);
			}
			$visits_count=count($visits_user[0]);
		}

		if(array_key_exists("determined_date",$input)){
			$request->session()->put('print_determined_date',$input['determined_date']);
		}
		else {
			$request->session()->put('print_data_fromdate',$input['fromdate']);
			$request->session()->put('print_data_todate',$input['todate']);
		}
		$r2_active='active';
		if(!array_key_exists("determined_date",$input)){
		$header=" بيانات حجز كشف المرضي فى مكاتب حجز التذاكر خلال الفترة من ".$request->session()->get('print_data_fromdate')." ألى "
				 .$request->session()->get('print_data_todate');
		}
		else {
			$header=" بيانات حجز كشف المرضي فى مكاتب حجز التذاكر فى تاريخ : ".$request->session()->get('print_determined_date');
		}
		return view('report_visits',compact('r2_active','visits_user','reception_users','header'));


	}
	public function report_visits_period(Request $request){

		$today_date=null;
		if($request->session()->get('print_data_fromdate'))
			$header=" بيانات حجز كشف المرضي فى مكاتب حجز التذاكر خلال الفترة <br> من ".$request->session()->get('print_data_fromdate')." ألى "
					 .$request->session()->get('print_data_todate');
		else{
			$header=" بيانات حجز كشف المرضي فى مكاتب حجز التذاكر <br> فى تاريخ ".$request->session()->get('print_determined_date');
			$today_date=true;
		}
		$visits_count=0;
		if($receiptionists=$request->session()->get('receiptionists')){
			for($i=0;$i<count($receiptionists);$i++){
				if($request->session()->get('print_determined_date')){
					$visits_user[$i] = $this->_getVisits('c',1,'',5,$receiptionists[$i]->id,$request->session()->get('print_determined_date'));
				}
				else {
					$visits_user[$i]= $this->_getVisits('c',1,'',5,$receiptionists[$i]->id,$request->session()->get('print_data_fromdate'),$request->session()->get('print_data_todate'));
				}
				$visits_count+=count($visits_user[$i]);
			}
		}
		else{
			$receiptionist=$request->session()->get('receiptionist');
			if($request->session()->get('print_determined_date')){
				$visits_user[0]= $this->_getVisits('c',1,'',5,$receiptionist->id,$request->session()->get('print_determined_date'));
			}
			else {
				$visits_user[0]= $this->_getVisits('c',1,'',5,$receiptionist->id,$request->session()->get('print_data_fromdate'),$request->session()->get('print_data_todate'));
			}
			$visits_count=count($visits_user[0]);
		}


		return view('reports.visits_today',array('data'=>$visits_user,'table_header'=>$header,'medical_type'=>'c','numberOfVisits'=>$visits_count,'today_date'=>$today_date));
	}

	public function print_desk_visits_period(){
		$this->flash_report_sessions();
		$desk_users=User::where('role_id',7)->lists('name','id');
		$r8_active='active';
		return view('report_desk_visits',compact('r8_active','desk_users'));
	}
	public function show_desk_visits_period(Request $request){

		$this->flash_report_sessions();
		$input=$request->all();
		if($input['submit'] == "reload"){
			return redirect()->action('AdminController@print_desk_visits_period');
		}

		if(array_key_exists("determined_date",$input)){
			$messages = [
				'determined_date.required' => 'حقل تاريخ معين مطلوب الأدخال .',
				'determined_date.date' => 'حقل تاريخ معين يجب أن يكون تاريخ .'
			];
			$this->validate($request, [
				'determined_date' => 'required|date',
			],$messages);
		}
		else{
			$messages = [
				'fromdate.required' => 'حقل تاريخ من مطلوب الأدخال.',
				'fromdate.date' => 'تاريخ من يجب أن يكون تاريخ فقط.',
				'todate.required' => 'حقل تاريخ ألى مطلوب الأدخال.',
				'todate.date' => 'تاريخ ألى يجب أن يكون تاريخ فقط.',
				'todate.after' => ' حقل تاريخ ألى يجب أن يكون أكبر من التاريخ من .',
			];
			$this->validate($request, [
				'fromdate' => 'required|date',
				'todate' => 'required|date|after:fromdate',
			],$messages);
		}
		$desk_users=User::where('role_id',7)->lists('name','id');
		$input['reception_name']=isset($input['reception_name'])?$input['reception_name']:null;

		if(is_null($input['reception_name'])){
			$receiptionists=User::withTrashed()->where('role_id',7)->get();
			$request->session()->put('receiptionists',$receiptionists);
			$visits_count=0;
			for($i=0;$i<count($receiptionists);$i++){
				if(array_key_exists("determined_date",$input)){
					$visits_user[$i] = $this->_getVisits('c',1,'',7,$receiptionists[$i]->id,$input['determined_date']);
				}
				else {
					$visits_user[$i] = $this->_getVisits('c',1,'',7,$receiptionists[$i]->id,$input['fromdate'],$input['todate']);
				}
				$visits_count+=count($visits_user[$i]);
			}
		}
		else{
			$receiptionist=User::find($input['reception_name']);
			$request->session()->put('receiptionist',$receiptionist);
			if(array_key_exists("determined_date",$input)){
				$visits_user[0]= $this->_getVisits('c',1,'',7,$input['reception_name'],$input['determined_date']);
			}
			else {
				$visits_user[0]= $this->_getVisits('c',1,'',7,$input['reception_name'],$input['fromdate'],$input['todate']);
			}
			$visits_count=count($visits_user[0]);
		}

		if(array_key_exists("determined_date",$input)){
			$request->session()->put('print_determined_date',$input['determined_date']);
		}
		else {
			$request->session()->put('print_data_fromdate',$input['fromdate']);
			$request->session()->put('print_data_todate',$input['todate']);
		}
		$r8_active='active';
		if(!array_key_exists("determined_date",$input)){
		$header=" بيانات حجز كشف المرضي فى مكاتب الأستقبال خلال الفترة من ".$request->session()->get('print_data_fromdate')." ألى "
				 .$request->session()->get('print_data_todate');
		}
		else {
			$header=" بيانات حجز كشف المرضي فى مكاتب الأستقبال فى تاريخ : ".$request->session()->get('print_determined_date');
		}
		return view('report_desk_visits',compact('r8_active','visits_user','desk_users','header'));


	}
	public function report_desk_visits_period(Request $request){

		$today_date=null;
		if($request->session()->get('print_data_fromdate'))
			$header=" بيانات حجز كشف المرضي فى مكاتب الأستقبال خلال الفترة <br> من ".$request->session()->get('print_data_fromdate')." ألى "
					 .$request->session()->get('print_data_todate');
		else{
			$header=" بيانات حجز كشف المرضي فى مكاتب الأستقبال <br> فى تاريخ ".$request->session()->get('print_determined_date');
			$today_date=true;
		}
		$visits_count=0;
		if($receiptionists=$request->session()->get('receiptionists')){
			for($i=0;$i<count($receiptionists);$i++){
				if($request->session()->get('print_determined_date')){
					$visits_user[$i] = $this->_getVisits('c',1,'',7,$receiptionists[$i]->id,$request->session()->get('print_determined_date'));
				}
				else {
					$visits_user[$i]= $this->_getVisits('c',1,'',7,$receiptionists[$i]->id,$request->session()->get('print_data_fromdate'),$request->session()->get('print_data_todate'));
				}
				$visits_count+=count($visits_user[$i]);
			}
		}
		else{
			$receiptionist=$request->session()->get('receiptionist');
			if($request->session()->get('print_determined_date')){
				$visits_user[0]= $this->_getVisits('c',1,'',7,$receiptionist->id,$request->session()->get('print_determined_date'));
			}
			else {
				$visits_user[0]= $this->_getVisits('c',1,'',7,$receiptionist->id,$request->session()->get('print_data_fromdate'),$request->session()->get('print_data_todate'));
			}
			$visits_count=count($visits_user[0]);
		}


		return view('reports.visits_today',array('data'=>$visits_user,'table_header'=>$header,'medical_type'=>'c','numberOfVisits'=>$visits_count,'today_date'=>$today_date));
	}


	public function print_rec_desk_visits_period(){
		$this->flash_report_sessions();
		$r9_active='active';
		return view('report_rec_desk_visits',compact('r9_active'));
	}
	public function show_rec_desk_visits_period(Request $request){

		$this->flash_report_sessions();
		$input=$request->all();
		if($input['submit'] == "reload"){
			return redirect()->action('AdminController@print_rec_desk_visits_period');
		}

		if(array_key_exists("determined_date",$input)){
			$messages = [
				'determined_date.required' => 'حقل تاريخ معين مطلوب الأدخال .',
				'determined_date.date' => 'حقل تاريخ معين يجب أن يكون تاريخ .'
			];
			$this->validate($request, [
				'determined_date' => 'required|date',
			],$messages);
		}
		else{
			$messages = [
				'fromdate.required' => 'حقل تاريخ من مطلوب الأدخال.',
				'fromdate.date' => 'تاريخ من يجب أن يكون تاريخ فقط.',
				'todate.required' => 'حقل تاريخ ألى مطلوب الأدخال.',
				'todate.date' => 'تاريخ ألى يجب أن يكون تاريخ فقط.',
				'todate.after' => ' حقل تاريخ ألى يجب أن يكون أكبر من التاريخ من .',
			];
			$this->validate($request, [
				'fromdate' => 'required|date',
				'todate' => 'required|date|after:fromdate',
			],$messages);
		}

		if(array_key_exists("determined_date",$input)){
			$data=$this->_getRecDeskVisits($input['determined_date']);
		}
		else{
			$data=$this->_getRecDeskVisits($input['fromdate'],$input['todate']);
		}

		if(array_key_exists("determined_date",$input)){
			$request->session()->put('print_determined_date',$input['determined_date']);
		}
		else {
			$request->session()->put('print_data_fromdate',$input['fromdate']);
			$request->session()->put('print_data_todate',$input['todate']);
		}
		$r9_active='active';
		if(!array_key_exists("determined_date",$input)){
		$header=" بيانات مرضي المحولون من حجز العيادات الي الأستقبال خلال الفترة من ".$request->session()->get('print_data_fromdate')." ألى "
				 .$request->session()->get('print_data_todate');
		}
		else {
			$header=" بيانات مرضى المحولون من حجز العيادات الي الاستقبال فى تاريخ : ".$request->session()->get('print_determined_date');
		}
		return view('report_rec_desk_visits',compact('r9_active','data','header'));


	}
	public function report_rec_desk_visits_period(Request $request){

		$today_date=null;
		if($request->session()->get('print_data_fromdate'))
			$header=" بيانات مرضي المحولون من حجز العيادات الي الاستقبال خلال الفترة <br> من ".$request->session()->get('print_data_fromdate')." ألى "
					 .$request->session()->get('print_data_todate');
		else{
			$header=" بيانات مرضى المحولون من حجز العيادات الي الاستقبال <br> فى تاريخ ".$request->session()->get('print_determined_date');
			$today_date=true;
		}
		$visits_count=0;
		if($request->session()->get('print_determined_date')){
			$data=$this->_getRecDeskVisits($request->session()->get('print_determined_date'));
		}
		else{
			$data=$this->_getRecDeskVisits($request->session()->get('print_data_fromdate'),$request->session()->get('print_data_todate'));
		}
		$visits_count=count($data);
		return view('reports.rec_to_desk',array('data'=>$data,'table_header'=>$header,'medical_type'=>'c','numberOfVisits'=>$visits_count,'today_date'=>$today_date));
	}


	public function show_total_patient_view(){
		$this->flash_report_sessions();
		$r3_active='active';
		return view('total_patients',compact('r3_active'));
	}

	public function show_total_patient_results(Request $request){

		$this->flash_report_sessions();
		$input=$request->all();
		if($input['submit'] == "reload"){
			return redirect()->action('AdminController@show_total_patient_view');
		}
		if(array_key_exists("determined_date",$input)){
			$messages = [
				'determined_date.required' => 'حقل تاريخ معين مطلوب الأدخال .',
				'determined_date.date' => 'حقل تاريخ معين يجب أن يكون تاريخ .'
			];
			$this->validate($request, [
				'determined_date' => 'required|date',
			],$messages);
		}
		else{
			$messages = [
				'fromdate.required' => 'حقل تاريخ من مطلوب الأدخال.',
				'fromdate.date' => 'تاريخ من يجب أن يكون تاريخ فقط',
				'todate.required' => 'حقل تاريخ ألى مطلوب الأدخال.',
				'todate.date' => 'تاريخ ألى يجب أن يكون تاريخ فقط',
				'todate.after' => ' حقل تاريخ ألى يجب أن يكون أكبر من التاريخ من ',
			];
			$this->validate($request, [
				'fromdate' => 'required|date',
				'todate' => 'required|date|after:fromdate',
			],$messages);
		}

		if(array_key_exists("determined_date",$input)){
			$data=$this->_getNumberVisits('c',$input['determined_date']);
		}
		else{
			$data=$this->_getNumberVisits('c',$input['fromdate'],$input['todate']);
		}
		if(array_key_exists("determined_date",$input)){
			$request->session()->put('print_determined_date',$input['determined_date']);
		}
		else {
			$request->session()->put('print_data_fromdate',$input['fromdate']);
			$request->session()->put('print_data_todate',$input['todate']);
		}

		$r3_active='active';
		if(array_key_exists("determined_date",$input)){
			$header="  تقرير إجمالي عدد الحالات في تاريخ ".$request->session()->get('print_determined_date');

		}
		else {
			$header="  تقرير إجمالي عدد الحالات خلال الفترة من ".$request->session()->get('print_data_fromdate')." ألى "
					   .$request->session()->get('print_data_todate');

		}
		return view('total_patients',compact('r3_active','data','header'));


	}

	public function report_total_patients_period(Request $request){

		if($request->session()->get('print_data_fromdate'))
			$header="  تقرير إجمالي عدد الحالات خلال الفترة <br> من ".$request->session()->get('print_data_fromdate')." ألى "
					 .$request->session()->get('print_data_todate');
		else {
			$header="  تقرير إجمالي عدد الحالات <br> فى تاريخ ".$request->session()->get('print_determined_date');

		}
		if($request->session()->get('print_determined_date')){
			$visits=$this->_getNumberVisits('c',$request->session()->get('print_determined_date'));
		}
		else{
			$visits=$this->_getNumberVisits('c',$request->session()->get('print_data_fromdate'),$input['print_data_todate']);
		}

		return view('reports.total_patients_period',array('data'=>$visits,'table_header'=>$header,'medical_type'=>'c'));
	}

	public function report_total_patients_today(){

		$header=" تقرير إجمالي عدد الحالات بتاريخ ".date('d-m-Y');$medical_type="c";
		$current_date=date("Y-m-d",time());
		$visits = $this->_getNumberVisits($medical_type,$current_date);
		return view('reports.total_patients_period',array('data'=>$visits,'table_header'=>$header,'medical_type'=>$medical_type));

	}

	public function print_inpatient_today(){

			$current_user=User::find(Auth::id());
			if($current_user->role->name == "Doctor" || $current_user->role->name == "Nursing")
				return redirect()->guest('auth/login');
			$table_header=" بيانات مرضي الدخول بتاريخ ".date("d-m-Y");
			$r4_active="active";
			$determined_date=true;
			$role_name=$current_user->role->name;
			if($current_user->role->id == 4 || $current_user->role->id == 5 || $current_user->role->id == 7){
				$data[0]= $this->__getInpatientVisits($current_user);
				$numberOfVisits=count($data[0]);
				return view('reports.inpatients_today',compact('r4_active','data','table_header','numberOfVisits','role_name'));
			}
			else{
				$numberOfVisits=0;
				$reception_users=User::whereIn('role_id',[4,5,7])->get();
				for($i=0;$i<count($reception_users);$i++){
					$data[$i] = $this->__getInpatientVisits($reception_users[$i]);
					$numberOfVisits+=count($data[$i]);
				}
				return view('reports.inpatients_today',compact('r4_active','data','table_header','numberOfVisits','determined_date'));
			}
	}

	public function print_inpatients_visits_period(){
		$this->flash_report_sessions();
		$reception_users=User::whereIn('role_id',[4,5])->lists('name','id');
		$r5_active='active';
		return view('report_inpatient_visits',compact('r5_active','reception_users'));
	}
	public function show_inpatients_visits_period(Request $request){

		$this->flash_report_sessions();
		$input=$request->all();
		if($input['submit'] == "reload"){
			return redirect()->action('AdminController@print_inpatients_visits_period');
		}

		if(array_key_exists("determined_date",$input)){
			$messages = [
				'determined_date.required' => 'حقل تاريخ معين مطلوب الأدخال .',
				'determined_date.date' => 'حقل تاريخ معين يجب أن يكون تاريخ .'
			];
			$this->validate($request, [
				'determined_date' => 'required|date',
			],$messages);
		}
		else{
			$messages = [
				'fromdate.required' => 'حقل تاريخ من مطلوب الأدخال.',
				'fromdate.date' => 'تاريخ من يجب أن يكون تاريخ فقط.',
				'todate.required' => 'حقل تاريخ ألى مطلوب الأدخال.',
				'todate.date' => 'تاريخ ألى يجب أن يكون تاريخ فقط.',
				'todate.after' => ' حقل تاريخ ألى يجب أن يكون أكبر من التاريخ من .',
			];
			$this->validate($request, [
				'fromdate' => 'required|date',
				'todate' => 'required|date|after:fromdate',
			],$messages);
		}
		$reception_users=User::whereIn('role_id',[4,5])->lists('name','id');
		$input['reception_name']=isset($input['reception_name'])?$input['reception_name']:null;

		if(is_null($input['reception_name'])){
			$receiptionists=User::withTrashed()->whereIn('role_id',[4,5,7])->get();
			$request->session()->put('receiptionists',$receiptionists);
			$visits_count=0;
			for($i=0;$i<count($receiptionists);$i++){
				if(array_key_exists("determined_date",$input)){
					$visits_user[$i] = $this->__getInpatientVisits($receiptionists[$i],$input['determined_date']);
				}
				else {
					$visits_user[$i] = $this->__getInpatientVisits($receiptionists[$i],$input['fromdate'],$input['todate']);
				}
				$visits_count+=count($visits_user[$i]);
			}
		}
		else{
			$receiptionist=User::find($input['reception_name']);
			$request->session()->put('receiptionist',$receiptionist);
			if(array_key_exists("determined_date",$input)){
				$visits_user[0]= $this->__getInpatientVisits($receiptionist,$input['determined_date']);
			}
			else {
				$visits_user[0]= $this->__getInpatientVisits($receiptionist,$input['fromdate'],$input['todate']);
			}
			$visits_count=count($visits_user[0]);
		}
		if(array_key_exists("determined_date",$input)){
			$request->session()->put('print_determined_date',$input['determined_date']);
		}
		else {
			$request->session()->put('print_data_fromdate',$input['fromdate']);
			$request->session()->put('print_data_todate',$input['todate']);
		}
		$r5_active='active';
		$determined_date=false;
		if(!array_key_exists("determined_date",$input)){
			$header=" بيانات مرضى الدخول خلال فترة  من  ".$request->session()->get('print_data_fromdate')." ألى "
					.$request->session()->get('print_data_todate');
				 $determined_date=false;
		}
		else {
			$header=" بيانات مرضى الدخول فى تاريخ : ".$request->session()->get('print_determined_date');
			$determined_date=true;
		}
		return view('report_inpatient_visits',compact('r5_active','visits_user','reception_users','header','determined_date'));


	}

	public function report_inpatient_period(Request $request){

		$determined_date=false;
		if($request->session()->get('print_data_fromdate')){
			$header=" بيانات مرضى الدخول خلال الفترة <br> من ".$request->session()->get('print_data_fromdate')." ألى "
					 .$request->session()->get('print_data_todate');
					$determined_date=false;
		}
		else{
			$header=" بيانات مرضى الدخول <br> فى تاريخ ".$request->session()->get('print_determined_date');
			$determined_date=true;
		}
		$visits_count=0;
		if($receiptionists=$request->session()->get('receiptionists')){

			for($i=0;$i<count($receiptionists);$i++){
				if($request->session()->get('print_determined_date')){
					$visits_user[$i] = $this->__getInpatientVisits($receiptionists[$i],$request->session()->get('print_determined_date'));
				}
				else {
					$visits_user[$i] = $this->__getInpatientVisits($receiptionists[$i],$request->session()->get('print_data_fromdate'),$request->session()->get('print_data_todate'));
				}
				$visits_count+=count($visits_user[$i]);
			}
		}
		else{
			$receiptionist=$request->session()->get('receiptionist');
			if($request->session()->get('print_determined_date')){
				$visits_user[0]= $this->__getInpatientVisits($receiptionist,$request->session()->get('print_determined_date'));
			}
			else {
				$visits_user[0]= $this->__getInpatientVisits($receiptionist,$request->session()->get('print_data_fromdate'),$request->session()->get('print_data_todate'));
			}
			$visits_count=count($visits_user[0]);
		}

		return view('reports.inpatients_today',array('data'=>$visits_user,'table_header'=>$header,'numberOfVisits'=>$visits_count,'determined_date'=>$determined_date));
	}

	public function report_print_medicines(){
		$this->flash_report_sessions();
		$r10_active='active';
		return view('report_print_medicines',compact('r10_active'));
	}

	public function report_print_medicines_results(Request $request){

		$this->flash_report_sessions();
		$input=$request->all();
		if($input['submit'] == "reload"){
			return redirect()->action('AdminController@report_print_medicines');
		}
		if(array_key_exists("determined_date",$input)){
			$messages = [
				'determined_date.required' => 'حقل تاريخ معين مطلوب الأدخال .',
				'determined_date.date' => 'حقل تاريخ معين يجب أن يكون تاريخ .'
			];
			$this->validate($request, [
				'determined_date' => 'required|date',
			],$messages);
		}
		else{
			$messages = [
				'fromdate.required' => 'حقل تاريخ من مطلوب الأدخال.',
				'fromdate.date' => 'تاريخ من يجب أن يكون تاريخ فقط',
				'todate.required' => 'حقل تاريخ ألى مطلوب الأدخال.',
				'todate.date' => 'تاريخ ألى يجب أن يكون تاريخ فقط',
				'todate.after' => ' حقل تاريخ ألى يجب أن يكون أكبر من التاريخ من ',
			];
			$this->validate($request, [
				'fromdate' => 'required|date',
				'todate' => 'required|date|after:fromdate',
			],$messages);
		}

		if(array_key_exists("determined_date",$input)){
			$data=$this->_getMedicines($input['determined_date']);
		}
		else{
			$data=$this->_getMedicines($input['fromdate'],$input['todate']);
		}
		if(array_key_exists("determined_date",$input)){
			$request->session()->put('print_determined_date',$input['determined_date']);
		}
		else {
			$request->session()->put('print_data_fromdate',$input['fromdate']);
			$request->session()->put('print_data_todate',$input['todate']);
		}

		$r10_active='active';
		if(array_key_exists("determined_date",$input)){
			$header=" تقرير الأدوية و المستلزمات فى تاريخ  ".$request->session()->get('print_determined_date');

		}
		else {
			$header=" تقرير الأدوية و المستلزمات خلال الفترة من ".$request->session()->get('print_data_fromdate')." ألى "
					   .$request->session()->get('print_data_todate');

		}
		return view('report_print_medicines',compact('r10_active','data','header'));
	}

	public function print_medicines_results(Request $request){
		if($request->session()->get('print_data_fromdate'))
			$header=" تقرير الأدوية و المستلزمات خلال الفترة <br> من ".$request->session()->get('print_data_fromdate')." ألى "
					 .$request->session()->get('print_data_todate');
		else{
			$header=" تقرير الأدوية و المستلزمات فى تاريخ <br> ".$request->session()->get('print_determined_date');
		}
		if($request->session()->get('print_determined_date')){
			$data=$this->_getMedicines($request->session()->get('print_determined_date'));
		}
		else{
			$data=$this->_getMedicines($request->session()->get('print_data_fromdate'),$request->session()->get('print_data_todate'));
		}

		return view('reports.medicines',array('data'=>$data,'table_header'=>$header));
	}


	public function editPatient($pid,$vid){

		$patient_data=Patient::find($pid);
		if($vid!=-1)
			$visit=Visit::find($vid);
		else
			$visit=null;
		$ages=array();
		$days = array();
		$ages[""]=0;
		$days[""]=0;
		for($i=1;$i<=11;$i++)
			$ages[$i]=$i;
		for($i=1;$i<=29;$i++)
			$days[$i]=$i;
		$s_active='active';
		return view('edit_patient',compact('patient_data','visit','vid','ages','days','s_active'));

	}

	public function updatePatient(Request $request, $pid,$vid){

		$input=$request->all();
		$messages = [
			'ticket_number.required' => 'هذا الحقل مطلوب الأدخال.',
			'ticket_number.numeric' => 'حقل رقم التذكرة يجب أن يكون رقم فقط .',
			'ticket_number.unique' => 'رقم التذكرة موجود من قبل .',
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

		];
		if(isset($input['ticket_number']))
		{
			$constraints['ticket_number']="required|numeric";
			if($input['ticket_type']==""){
				$clinic_ticket=Visit::where('ticket_number',$input['ticket_number'])
														->whereNull('ticket_type')
														->where('id','<>',$vid)
														->first();
				if(count($clinic_ticket) > 0){
						return redirect()->back()->withErrors(['ticket_number'=>'رقم التذكرة موجود من قبل .'])->withInput();
				}
			}
			else{
				$desk_ticket=Visit::where('ticket_number',$input['ticket_number'])
														->whereNotNull('ticket_type')
														->where('id','<>',$vid)
														->first();
				if(count($desk_ticket) > 0){
						return redirect()->back()->withErrors(['ticket_number'=>'رقم التذكرة موجود من قبل .'])->withInput();
				}
			}
		}

		$constraints['fname'] = 'required|alpha|min:2|max:20';
		$constraints['sname'] = 'required|alpha|min:2|max:20';
		$constraints['mname' ] = 'required|alpha|min:2|max:20';
		$constraints['lname' ] = 'alpha|min:2|max:20';
		$constraints['gender'] = 'required';
		$constraints['address'] ='required';
		$constraints['sid' ] = 'size:14|unique:patients,sid,'.$pid;
		$constraints['year_age' ] = 'numeric|required_without_all:month_age,day_age';

		$this->validate($request, $constraints ,$messages);
		$input['name']=$input['fname']." ".$input['sname']." ".$input['mname']." ".$input['lname'];
		unset($input['fname']);unset($input['sname']);unset($input['mname']);unset($input['lname']);
		if($input['sid'] != ""){
			if($input['sid'][0] == 2)
				$prifx_year="19";
			else if($input['sid'][0] == 3)
				$prifx_year="20";
			else if($input['sid'][0] == 4)
				$prifx_year="21";

			$year=$prifx_year."".$input['sid'][1]."".$input['sid'][2];
			$month=$input['sid'][3]."".$input['sid'][4];
			$day=$input['sid'][5]."".$input['sid'][6];
			$birthdate=$year."-".$month."-".$day;
			$arr['sid']=$birthdate;
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
			DB::beginTransaction();
			try{
				$patient=Patient::find($pid);
				$patient->sid=$input['sid'];
				$patient->name=$input['name'];
				$patient->gender=$input['gender'];
				$patient->address=$input['address'];
				$patient->birthdate=$birthdate;
				$patient->age=$input['year_age'];
				$patient->save();
				if(isset($input['ticket_number']))
					Visit::find($vid)->update(['ticket_number'=>$input['ticket_number']]);
				DB::commit();
			}
			catch(\Exception $e){
				DB::rollBack();
			}

		}
		else{
			$birthdate=strtotime("-".($input['day_age']==""?0:$input['day_age'])." day",time());
			$birthdate=strtotime("-".($input['month_age']==""?0:$input['month_age'])." month",$birthdate);
			$birthdate=strtotime("-".($input['year_age']==""?0:$input['year_age'])." year",$birthdate);

			$birthdate=date('Y-m-d',$birthdate);

			DB::beginTransaction();
			try{
				$patient=Patient::find($pid);
				$patient->name=$input['name'];
				$patient->gender=$input['gender'];
				$patient->address=$input['address'];
				$patient->birthdate=$birthdate;
				$patient->age=$input['year_age'];
				$patient->save();
				if(isset($input['ticket_number']))
					Visit::find($vid)->update(['ticket_number'=>$input['ticket_number']]);
				DB::commit();
			}
			catch(\Exception $e){
				DB::rollBack();
			}

		}
		$request->session()->flash('success','تم التعديل بنجاح');
		return redirect()->back();
	}

	public function searchTicketNumber()
	{
			$visits= Visit::with('patient')->where('visits.ticket_number',request()->ticket_number)->get();
			return redirect()->back()->withVisits($visits);
	}
	private function flash_report_sessions(){

		if(Session::has('receiptionist') || Session::has('receiptionists') || Session::forget('print_data_fromdate') || Session::forget('print_determined_date')){
			Session::forget('receiptionist');
			Session::forget('receiptionists');
			Session::forget('print_data_fromdate');
			Session::forget('print_data_todate');
			Session::forget('print_determined_date');
		}
	}
	public function report_total_inpatients_period_view()
	{
		$this->flash_report_sessions();
		$reception_users=User::whereIn('role_id',[4,5,7])->lists('name','id');
		$r6_active='active';
		return view('report_inpatientdep_visits',compact('r6_active','reception_users'));
	}
	public function report_total_inpatients_period_results(request $request)
	{
		$this->flash_report_sessions();
		$input=$request->all();
		if($input['submit'] == "reload"){
			return redirect()->action('AdminController@report_total_inpatients_period_view');
		}

		if(array_key_exists("determined_date",$input)){
			$messages = [
				'determined_date.required' => 'حقل تاريخ معين مطلوب الأدخال .',
				'determined_date.date' => 'حقل تاريخ معين يجب أن يكون تاريخ .'
			];
			$this->validate($request, [
				'determined_date' => 'required|date',
			],$messages);
		}
		else{
			$messages = [
				'fromdate.required' => 'حقل تاريخ من مطلوب الأدخال.',
				'fromdate.date' => 'تاريخ من يجب أن يكون تاريخ فقط.',
				'todate.required' => 'حقل تاريخ ألى مطلوب الأدخال.',
				'todate.date' => 'تاريخ ألى يجب أن يكون تاريخ فقط.',
				'todate.after' => ' حقل تاريخ ألى يجب أن يكون أكبر من التاريخ من .',
			];
			$this->validate($request, [
				'fromdate' => 'required|date',
				'todate' => 'required|date|after:fromdate',
			],$messages);
		}
		$reception_users=User::whereIn('role_id',[4,5,7])->lists('name','id');
		$input['reception_name']=isset($input['reception_name'])?$input['reception_name']:null;

		if(is_null($input['reception_name'])){
			$receiptionists=User::withTrashed()->whereIn('role_id',[4,5,7])->get();
			$request->session()->put('receiptionists',$receiptionists);
			$visits_count=0;
			for($i=0;$i<count($receiptionists);$i++){
				if(array_key_exists("determined_date",$input)){
					$visits_user[$i] = $this->__getInpatientVisitsHasr($receiptionists[$i],$input['determined_date']);
				}
				else {
					$visits_user[$i] = $this->__getInpatientVisitsHasr($receiptionists[$i],$input['fromdate'],$input['todate']);
				}
				$visits_count+=count($visits_user[$i]);
			}
		}
		else{
			$receiptionist=User::find($input['reception_name']);
			$request->session()->put('receiptionist',$receiptionist);
			if(array_key_exists("determined_date",$input)){
				$visits_user[0]= $this->__getInpatientVisitsHasr($receiptionist,$input['determined_date']);
			}
			else {
				$visits_user[0]= $this->__getInpatientVisitsHasr($receiptionist,$input['fromdate'],$input['todate']);
			}
			$visits_count=count($visits_user[0]);
		}
		//$request->session()->put('print_data',$visits_user);
		if(array_key_exists("determined_date",$input)){
			$request->session()->put('print_determined_date',$input['determined_date']);
		}
		else {
			$request->session()->put('print_data_fromdate',$input['fromdate']);
			$request->session()->put('print_data_todate',$input['todate']);
		}
		$r6_active='active';
		$determined_date=false;
		if(!array_key_exists("determined_date",$input)){
			$header=" بيان بحصر دخول مرضى القسم الداخلي".$request->session()->get('print_data_fromdate')." ألى "
					.$request->session()->get('print_data_todate');
				 $determined_date=false;
		}
		else {
			$header=" بيان بحصر دخول مرضى القسم الداخلي بتاريخ : ".$request->session()->get('print_determined_date');
			$determined_date=true;
		}
		return view('report_inpatientdep_visits',compact('r6_active','visits_user','reception_users','header','determined_date'));


	}
	private function __getInpatientVisitsHasr($user,$fromdate='',$todate='')
	{
				return Visit::join('patients', 'patients.id', '=', 'visits.patient_id')
					->where(function($query) use ($user,$fromdate,$todate){
						// Reception or Entrypoint
						if($user->role->id == 5 || $user->role->id == 4 || $user->role->id == 7)
						{
							$query->where('visits.user_id',$user->id);
						}
						if($fromdate != ""){
							if($todate != ""){
								$query->whereDate('visits.entry_date','>=',$fromdate);
								$query->whereDate('visits.entry_date','<=',$todate);
							}
							else{

								$query->whereDate('visits.entry_date','=',$fromdate);
							}
						}
						else{
								$query->whereDate('visits.entry_date','=',date('Y-m-d'));
						}
					})
					//->where('type','d')
					->where('cancelled',false)
					->where('visits.closed','1')
					->select(DB::raw(" (select name from users where users.id=visits.user_id) as user_name "),
					'patients.name as name','visits.final_diagnosis as fd','visits.entry_date as ed',
					'visits.exit_date as exd'
					)
					->orderBy('visits.created_at', 'desc')->get();
	}
	public function print_inpatients_dep_period(Request $request)
	{
		$determined_date=false;
		if($request->session()->get('print_data_fromdate')){
			$header="  حصر دخول مرضى القسم الداخلي خلال الفترة <br> من".$request->session()->get('print_data_fromdate')." ألى "
					 .$request->session()->get('print_data_todate');
					$determined_date=false;
		}
		else{
			$header="حصر مرضى الدخول <br> في تاريخ".$request->session()->get('print_determined_date');
			$determined_date=true;
		}
		$visits_count=0;
		if($receiptionists=$request->session()->get('receiptionists')){
			for($i=0;$i<count($receiptionists);$i++){
				if($request->session()->get('print_determined_date')){
					$visits_user[$i] = $this->__getInpatientVisitsHasr($receiptionists[$i],$request->session()->get('print_determined_date'));
				}
				else {
					$visits_user[$i] = $this->__getInpatientVisitsHasr($receiptionists[$i],$request->session()->get('print_data_fromdate'),$request->session()->get('print_data_todate'));
				}
				$visits_count+=count($visits_user[$i]);
			}
		}
		else{
			$receiptionist=$request->session()->get('receiptionist');
			if($request->session()->get('print_determined_date')){
				$visits_user[0]= $this->__getInpatientVisitsHasr($receiptionist,$input['determined_date']);
			}
			else {
				$visits_user[0]= $this->__getInpatientVisitsHasr($receiptionist,$request->session()->get('print_data_fromdate'),$request->session()->get('print_data_todate'));
			}
			$visits_count=count($visits_user[0]);
		}

		return view('reports.inpatients_dep_period',array('data'=>$visits_user,'table_header'=>$header,'numberOfVisits'=>$visits_count,'determined_date'=>$determined_date));

	}

	public function _getVisits($medical_type,$type,$entrypoint='',$user_role_id,$receiptionist_id,$from_date='',$to_date=''){

		return  Visit::join('medical_unit_visit', 'visits.id', '=', 'medical_unit_visit.visit_id')
					  ->join('medical_units','medical_unit_visit.medical_unit_id','=','medical_units.id')
					  ->join('patients','patients.id','=','visits.patient_id')
					  ->where(function($query) use ($user_role_id,$type,$entrypoint,$from_date,$to_date,$receiptionist_id){
							if($type == '0'){
								$query->whereDate('visits.created_at','=',date('Y-m-d'));
							}
							else{
								if($to_date == '')
									$query->whereDate('visits.created_at','=',$from_date);
								else{
									$query->whereDate('visits.created_at','>=',$from_date);
									$query->whereDate('visits.created_at','<=',$to_date);
								}

							}
							// Reception is role number 5
							if($user_role_id == 5){
								if($entrypoint != ""){
									$query->where('entry_id','=',$entrypoint->id);
								}
							}

							if($receiptionist_id != null && $user_role_id == 5){
								$query->where('visits.user_id',$receiptionist_id);
							}
					  })
					  ->where(function($query) use($entrypoint,$user_role_id,$receiptionist_id){
						    if($user_role_id == 7)
							{
								if($entrypoint != "")
									$query->where('entry_id','=',$entrypoint->id)
										  ->orWhere('convert_to_entry_id','=',$entrypoint->id);
							}
							if($receiptionist_id != null && $user_role_id == 7){
								$query->where('visits.user_id',$receiptionist_id);
							}
					  })
					  ->where('type','=',$medical_type)
					  ->where('cancelled',false)
						->where(function ($query) {
							$query->whereNull('medical_unit_visit.convert_to')
								  ->orWhere('department_conversion',1);
						})

					  ->select(DB::raw(" (select name from users where users.id=visits.user_id) as user_name "),'patients.id','patients.name','visits.ticket_number','ticket_type','patients.gender','patients.address','patients.birthdate','patients.sid','medical_units.name as dept_name','visits.created_at')
					  ->orderBy('visits.created_at','asc')
					  ->get();
	}
	public function _getNumberVisits($medical_type,$from_date,$to_date=''){

		return Visit::join('medical_unit_visit', 'visits.id', '=', 'medical_unit_visit.visit_id')
					->join('medical_units','medical_unit_visit.medical_unit_id','=','medical_units.id')
					->where(function($query) use ($from_date,$to_date){
						if($to_date != ""){
							$query->whereDate('visits.created_at','>=',$from_date);
							$query->whereDate('visits.created_at','<=',$to_date);
						}
						else{
							$query->whereDate('visits.created_at','=',$from_date);
						}
					})
					->where('type','=',$medical_type)
					->where('cancelled',false)
					->where(function ($query) {
							$query->whereNull('medical_unit_visit.convert_to')
										->orWhere('department_conversion',1);
					})
					->groupBy('medical_unit_id')
					->select(DB::raw(' count(*) as numberOfVisits'),'medical_units.name')
					->get();
	}

	private function __getInpatientVisits($user,$fromdate='',$todate='')
	{
		return Visit::join('medical_unit_visit', 'visits.id', '=', 'medical_unit_visit.visit_id')
					->join('medical_units','medical_unit_visit.medical_unit_id','=','medical_units.id')
					->join('patients','patients.id','=','visits.patient_id')
					->where(function($query) use ($user,$fromdate,$todate){
						// Reception or Entrypoint
						if($user->role->id == 5 || $user->role->id == 4 || $user->role->id == 7 )
						{
							$query->where('visits.user_id',$user->id);
						}
						if($fromdate != ""){
							if($todate != ""){
								$query->whereDate('visits.created_at','>=',$fromdate);
								$query->whereDate('visits.created_at','<=',$todate);
							}
							else{
								$query->whereDate('visits.created_at','=',$fromdate);
							}
						}
						else{
								$query->whereDate('visits.created_at','=',date('Y-m-d'));
						}
					})
					->where('type','d')
					->where('cancelled',false)
					->select(DB::raw(" (select name from users where users.id=visits.user_id) as user_name "),
						DB::raw(" (select name from relations where relations.id=visits.relation_id) as rel_name "),
					'patients.id','patients.name','patients.gender','patients.sid',
					'medical_units.name as dept_name', 'ticket_number','ticket_type', 'entry_time','entry_date', 'c_name','visits.sid as c_sid',
					'visits.id as visit_id','visits.created_at','visits.job','visits.address'
					)
					->orderBy('visits.id', 'desc')
					->orderBy('medical_unit_visit.created_at', 'desc')->get();
	}
	public function _getMedicines($from_date,$to_date=''){


		return Visit::join('patients','patients.id','=','visits.patient_id')
								->join('visit_diagnoses','visits.id','=','visit_diagnoses.visit_id')
								->join('visit_medicines','visits.id','=','visit_medicines.visit_id')
								->where(function($query) use ($from_date,$to_date){
									if($to_date != ""){
										$query->whereDate('visit_diagnoses.created_at','>=',$from_date);
										$query->whereDate('visit_diagnoses.created_at','<=',$to_date);
									}
									else{
										$query->whereDate('visit_diagnoses.created_at','=',$from_date);
									}
								})
								->orWhere(function($query) use ($from_date,$to_date){
									if($to_date != ""){
										$query->whereDate('visit_medicines.created_at','>=',$from_date);
										$query->whereDate('visit_medicines.created_at','<=',$to_date);
									}
									else{
										$query->whereDate('visit_medicines.created_at','=',$from_date);
									}
								})
								->groupBy('visits.id')
								->select(DB::raw("patients.name,( select GROUP_CONCAT(cure_description) as content from visit_diagnoses  where visit_diagnoses.visit_id=visits.id
								) as v_cure
								,
								( select GROUP_CONCAT(name) as name from visit_medicines  where visit_medicines.visit_id=visits.id
								) as v_med
								,
								( select GROUP_CONCAT(accessories) as name from visit_medicines  where visit_medicines.visit_id=visits.id
								) as v_access_clinic
								,
								( select GROUP_CONCAT(accessories) as name from visit_diagnoses  where visit_diagnoses.visit_id=visits.id
								) as v_access_dep"))
								->get();
	}


	public function _getRecDeskVisits($from_date='',$to_date=''){

		return  Visit::join('medical_unit_visit', 'visits.id', '=', 'medical_unit_visit.visit_id')
					  ->join('medical_units','medical_unit_visit.medical_unit_id','=','medical_units.id')
					  ->join('patients','patients.id','=','visits.patient_id')
					  ->where(function($query) use ($from_date,$to_date){
							if($to_date == '')
								$query->whereDate('visits.created_at','=',$from_date);
							else{
								$query->whereDate('visits.created_at','>=',$from_date);
								$query->whereDate('visits.created_at','<=',$to_date);
							}
					  })
					  ->whereNotNull('convert_to_entry_id')
					  ->where('type','=','c')
					  ->where('cancelled',false)
						->where(function ($query) {
							$query->whereNull('medical_unit_visit.convert_to')
								  ->orWhere('department_conversion',1);
						})

					  ->select(DB::raw(" (select name from users where users.id=visits.user_id) as user_name "),'patients.id','patients.name','visits.ticket_number','ticket_type','patients.gender',
							   DB::raw(" (select name from entrypoints where entrypoints.id=visits.convert_to_entry_id) as entry_name "),
					  'patients.address','patients.birthdate','patients.sid','medical_units.name as dept_name','visits.created_at')
					  ->orderBy('visits.created_at','asc')
					  ->get();
	}
	public function make_backup(){
		$exitCode = Artisan::call('db:backup');
		return response()->download(Session::get('backup_file'));
	}
	public function restore_file(Request $request){

		$input=$request->all();
		$extension = File::extension($request->file('restore_file')->getClientOriginalName());
		if($extension != "sql")
			return redirect()->action('AdminController@index')->withErrors('ملف قاعدة البيانات غير صالح');

		$messages = [
			'restore_file.required' => 'من فضلك أختر قاعدة البيانات المراد استرجاعها.',
		];
		$this->validate($request, [
			'restore_file' => 'required',
		],$messages);

		$request->file('restore_file')->move(storage_path()."/dumps/",$request->file('restore_file')->getClientOriginalName());
		$exitCode = Artisan::call('db:restore',['dump'=>$request->file('restore_file')->getClientOriginalName()]);
		return redirect()->action('AdminController@index');
	}
}
