@extends('layouts.app')
@section('title')
تسجيل بيانات المرضي
@endsection
@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">تسحيل بيانات المرضي</li>
      </ol>
	  <h1>
        بيانات المريض
        <small>تسجيل بيانات المريض</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
	  <div id="overlay"></div>
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-12 col-xs-24">
          <!-- small box -->
			  <div class="box box-primary" dir="rtl">

				<!-- form start -->
				
				{!! Form::open(array('class'=>'form','id'=>'patient_form', 'name'=>'patient_form','onsubmit'=>'return removeDisabled()')) !!}
				  <div class="box-body">
				   	
					@if(Session::has('flash_message'))
						@if(Session::get('message_type')=="true")
						<div class="alert alert-success">
						@else
						<div class="alert alert-danger">
						@endif
							<b>{{ Session::get('flash_message') }}</b>
						@if(Session::get('message_type')=="true")
							<br>
							لطباعة بطاقة دخول <a href="printpatientdata/
							@if(Session::has('id'))	
								{{ Session::get('id') }}&{{ Session::get('vid') }}
							@endif"
							target="_blank"> اضغط هنا </a>
						@endif
						</div>
					@endif
					<div class="alert alert-danger" style="display: none"id="err_msg"></div>
					<div class="row">
						<div class="col-lg-4" style="float:right">
							<div class="form-group">
							
							  {!! Form::label('كود المريض',null) !!}
							  @if(old('patient_id') != "") 
							  {!! Form::text('id',null,array('class'=>'form-control','id'=>'pid','disabled','placeholder'=>'كود المريض','onkeypress'=>'return isNumber(event)')) !!}
							  @else
							  {!! Form::text('id',null,array('class'=>'form-control','id'=>'pid','placeholder'=>'كود المريض','onkeypress'=>'return isNumber(event)')) !!}
							  @endif
							  {!! Form::hidden('patient_id',null,array('id'=>'hidden_pid')) !!}
							</div>
							
							<div class="form-group @if($errors->has('sid')) has-error @endif">
							@if(old('patient_id') != "") 
							  {!! Form::label('رقم البطاقة',null,array('style'=>'color:red')) !!}
							  {!! Form::text('sid',null,array('size'=>'14','class'=>'form-control','disabled','id'=>'sid','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("sid")')) !!}
							@else
							  {!! Form::label('رقم البطاقة',null,array('style'=>'color:red')) !!}
							  {!! Form::text('sid',null,array('size'=>'14','class'=>'form-control','id'=>'sid','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("sid")')) !!}
							@endif
							  @if ($errors->has('sid'))<span class="help-block">{{$errors->first('sid')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('fname') || $errors->has('sname') || $errors->has('mname') || $errors->has('lname')) has-error @endif">
							  {!! Form::label('الأسم',null,array('style'=>'color:red')) !!} <br>
							   @if(old('patient_id') != "")
								{!! Form::text('fname',null,array('class'=>'form-control','disabled'=>'true','id'=>'fname','style'=>'width:24%;display:inline')) !!}
							   @else
								{!! Form::text('fname',null,array('class'=>'form-control','id'=>'fname','style'=>'width:24%;display:inline')) !!}
							   @endif
							   @if(old('patient_id') != "")
								{!! Form::text('sname',null,array('class'=>'form-control','id'=>'sname','disabled'=>'true','style'=>'width:24%;display:inline')) !!}
							   @else
								{!! Form::text('sname',null,array('class'=>'form-control','id'=>'sname','style'=>'width:24%;display:inline')) !!}
							   @endif
							   @if(old('patient_id') != "")
								{!! Form::text('mname',null,array('class'=>'form-control','id'=>'mname','disabled'=>'true','style'=>'width:24%;display:inline')) !!}
							   @else
								{!! Form::text('mname',null,array('class'=>'form-control','id'=>'mname','style'=>'width:24%;display:inline')) !!}
							   @endif
							   @if(old('patient_id') != "")
								{!! Form::text('lname',null,array('class'=>'form-control','id'=>'lname','disabled'=>'true','style'=>'width:24%;display:inline')) !!}
							   @else
								{!! Form::text('lname',null,array('class'=>'form-control','id'=>'lname','style'=>'width:24%;display:inline')) !!}
							   @endif
							   @if ($errors->has('fname') || $errors->has('sname') || $errors->has('mname') || $errors->has('lname'))
							  <span class="help-block">
								@if ($errors->has('fname'))
									{{$errors->first('fname')}}
								@elseif($errors->has('sname'))
									{{$errors->first('sname')}}
								@elseif($errors->has('mname'))
									{{$errors->first('mname')}}
								@elseif($errors->has('lname'))
									{{$errors->first('lname')}}
								@endif
								</span>
							  @endif
							</div>
							<div class="form-group @if($errors->has('gender')) has-error @endif">
							  {!! Form::label('النوع',null,array('style'=>'color:red')) !!}
							  @if(old('patient_id') != "")
								{!! Form::select('gender',[''=>'أخترالنوع','M' => 'ذكر', 'F' => 'أنثى'], '',['disabled','class'=>'form-control','id'=>'gender']); !!}
							  @else
								{!! Form::select('gender',[''=>'أخترالنوع','M' => 'ذكر', 'F' => 'أنثى'], '',['class'=>'form-control','id'=>'gender']); !!}
							  @endif
							  @if ($errors->has('gender'))<span class="help-block">{{$errors->first('gender')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('address')) has-error @endif">
							  {!! Form::label('العنوان',null,array('style'=>'color:red')) !!}
							  {!! Form::text('address',null,array('class'=>'form-control','placeholder'=>'العنوان','id'=>'address')) !!}
							   @if ($errors->has('address'))<span class="help-block">{{$errors->first('address')}}</span>@endif
							</div>
							
						</div>
						<div class="col-lg-4" style="float:right">
							<div class="form-group @if($errors->has('birthdate')) has-error @endif">
							  {!! Form::label('birthdate','تاريخ الميلاد') !!}
							  @if(old('patient_id') != "") 
								{!! Form::text('birthdate',null,array('class'=>'form-control','id'=>'datepicker','disabled','placeholder'=>'1900-01-01')) !!}
							  @else
								{!! Form::text('birthdate',null,array('class'=>'form-control','id'=>'datepicker','placeholder'=>'1900-01-01')) !!}
							  @endif
							  @if ($errors->has('birthdate'))<span class="help-block">{{$errors->first('birthdate')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('age')) has-error @endif">
							  {!! Form::label('السن') !!}
							  {!! Form::text('age',null,array('class'=>'form-control','readonly'=>'readonly','min'=>'1','id'=>'age')) !!}
							  @if ($errors->has('age'))<span class="help-block">{{$errors->first('age')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('job')) has-error @endif">
							  {!! Form::label('المهنة') !!}
							  {!! Form::text('job',null,array('class'=>'form-control','placeholder'=>'المهنة','id'=>'job')) !!}
							  @if ($errors->has('job'))<span class="help-block">{{$errors->first('job')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('social_status')) has-error @endif">
							  {!! Form::label('الحالة الاجتماعية') !!}
							  {!! Form::text('social_status',null,array('class'=>'form-control','placeholder'=>' الحالة الاجتماعية ','id'=>'social_status')) !!}
							  @if ($errors->has('social_status'))<span class="help-block">{{$errors->first('social_status')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('phone_num')) has-error @endif">
							  {!! Form::label('رقم التليفون') !!}
							  {!! Form::text('phone_num',null,array('class'=>'form-control','min'=>'1','placeholder'=>'رقم التليفون','onkeypress'=>'return isNumber(event)','id'=>'phone_num')) !!}
							  @if ($errors->has('phone_num'))<span class="help-block">{{$errors->first('phone_num')}}</span>@endif
							</div>
						</div>
						<div class="col-lg-4" style="float:right">
							<fieldset>
								<legend>بيانات المرافق</legend>
								<div class="form-group @if($errors->has('c_name')) has-error @endif">
								  {!! Form::label('أسم المرافق',null,array('style'=>'color:red')) !!}
								  {!! Form::text('c_name',null,array('class'=>'form-control','placeholder'=>'الاسم')) !!}
								  
								  @if ($errors->has('c_name'))<span class="help-block">{{$errors->first('c_name')}}</span>@endif
								</div>
								<div class="form-group @if($errors->has('e_sid')) has-error @endif">
								  {!! Form::label('رقم البطاقة',null) !!}
								  {!! Form::text('e_sid',null,array('class'=>'form-control','id'=>'eid','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("eid")')) !!}
								  @if ($errors->has('e_sid'))<span class="help-block">{{$errors->first('e_sid')}}</span>@endif
								</div>
								<div class="form-group @if($errors->has('relation_id')) has-error @endif">
								  {!! Form::label('درجة القرابة') !!}
								  {!! Form::select('relation_id',$relations,null,['class' => 'form-control']); !!}
								  @if ($errors->has('relation_id'))<span class="help-block">{{$errors->first('relation_id')}}</span>@endif
								</div>
								<div class="form-group @if($errors->has('m_address')) has-error @endif">
								  {!! Form::label('محل الاقامة') !!}
								  {!! Form::text('m_address',null,array('class'=>'form-control','placeholder'=>'محل الاقامة')) !!}
								  @if ($errors->has('m_address'))<span class="help-block">{{$errors->first('m_address')}}</span>@endif
								</div>
								<div class="form-group @if($errors->has('m_job')) has-error @endif">
								  {!! Form::label('المهنة') !!}
								  {!! Form::text('m_job',null,array('class'=>'form-control','placeholder'=>'المهنة')) !!}
								  @if ($errors->has('m_job'))<span class="help-block">{{$errors->first('m_job')}}</span>@endif
								</div>
								<div class="form-group @if($errors->has('m_phone_num')) has-error @endif">
								  {!! Form::label('رقم التليفون') !!}
								  {!! Form::text('m_phone_num',null,array('class'=>'form-control','min'=>'1','onkeypress'=>'return isNumber(event)','placeholder'=>'رقم التليفون')) !!}
								  @if ($errors->has('m_phone_num'))<span class="help-block">{{$errors->first('m_phone_num')}}</span>@endif
								</div>
							</fieldset>
						</div>
					</div>
					<div class="box-header with-border">
					  <h3 class="box-title">تفاصيل اذن دخول المريض </h3>
					</div>
					<div class="row">
						<div class="col-lg-6" style="float:right">
						  <div class="form-group"  >
						   {!! Form::label('مكتب الدخول',null,array('style'=>'color:red')) !!}
						   {!! Form::select('entry_id',$entrypoints,null,['class' => 'form-control']); !!}
						  </div>
						  <div class="form-group" >
						   {!! Form::label('أسم القسم',null,array('style'=>'color:red')) !!}
						   {!! Form::select('medical_id',$medical_units,null,['id'=>'medical_id','class' => 'form-control']); !!}
						  </div>
						  <div class="form-group @if($errors->has('entry_date')) has-error @endif">
							  {!! Form::label('entry_date','تاريخ الدخول',array('style'=>'color:red')) !!}
							  {!! Form::text('entry_date',null,array('class'=>'form-control','id'=>'datepicker2','placeholder'=>'1900-01-01')) !!}
							  @if ($errors->has('entry_date'))<span class="help-block">{{$errors->first('entry_date')}}</span>@endif
						  </div>
						  <div class="bootstrap-timepicker">
							  <div class="form-group @if($errors->has('entry_time')) has-error @endif">
								  {!! Form::label('entry_time','ساعة الدخول',array('style'=>'color:red')) !!}
								  {!! Form::text('entry_time',null,array('class'=>'form-control timepicker')) !!}
    							  @if ($errors->has('entry_time'))<span class="help-block">{{$errors->first('entry_time')}}</span>@endif
							  </div>
						  </div>
						  <div class="form-group" >
						   {!! Form::label('الطبيب المعالج') !!}
						   {!! Form::select('reference_doctor_id',$first_department_doctors,null,['id'=>'reference_doctor_id','class' => 'form-control']); !!}
						  </div>
						</div>
						<div class="col-lg-6" style="float:right">
						  <div class="form-group @if($errors->has('room_number')) has-error @endif">
							  {!! Form::label('room_number','رقم الغرفة') !!}
							  {!! Form::text('room_number',null,array('class'=>'form-control','placeholder'=>'رقم الغرفة')) !!}
							  @if ($errors->has('room_number'))<span class="help-block">{{$errors->first('room_number')}}</span>@endif
						  </div>
						  <div class="form-group @if($errors->has('file_number')) has-error @endif">
							  {!! Form::label('file_number','رقم الملف',array('style'=>'color:red')) !!}
							  {!! Form::text('file_number',null,array('class'=>'form-control','placeholder'=>'رقم الملف')) !!}
							  @if ($errors->has('file_number'))<span class="help-block">{{$errors->first('file_number')}}</span>@endif
						  </div>
							<div class="form-group @if($errors->has('file_type')) has-error @endif">
									{!! Form::label('file_type','نوع الملف',array('style'=>'color:red')) !!}
									{!! Form::select('file_type',$file_types,'',['class' => 'form-control']); !!}
									@if ($errors->has('file_type'))<span class="help-block">{{$errors->first('file_type')}}</span>@endif
							</div>
						  <div class="form-group @if($errors->has('cure_type_id')) has-error @endif">
						      {!! Form::label('نوع العلاج') !!}
						      {!! Form::select('cure_type_id',$cure_types,null,['class' => 'form-control']); !!}
						  </div>
						  <div class="form-group @if($errors->has('contract')) has-error @endif">
							  {!! Form::label('contract','جهة التعاقد') !!}
							  {!! Form::text('contract',null,array('class'=>'form-control','placeholder'=>'جهة التعاقد')) !!}
							  @if ($errors->has('contract'))<span class="help-block">{{$errors->first('contract')}}</span>@endif
						  </div>
						  <div class="form-group @if($errors->has('converted_by_doctor')) has-error @endif">
							  {!! Form::label('محول بواسطة',null) !!}
							  {!! Form::text('converted_by_doctor',null,array('class'=>'form-control','placeholder'=>'محول بواسطة')) !!}
							  @if ($errors->has('converted_by_doctor'))<span class="help-block">{{$errors->first('converted_by_doctor')}}</span>@endif
						  </div>
						</div>
						
					</div>
				  </div>
				  <!-- /.box-body -->
				
				  <div class="box-footer">
					<button type="button" class="btn btn-primary" id="submitButton" onclick="$('#patient_form').submit();">تسجيل</button>
					<input type="reset" class="btn btn-success" onclick="$('#err_msg').hide();$('#submitButton').removeAttr('disabled');$('#datepicker').removeAttr('disabled');" 
					value="جديد"/>
				  </div>
				{!! Form::close() !!}
			  </div>
            <!-- ./box -->
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  
@endsection
@section('javascript')
<script>
$(document).ajaxStart(function(){
    $("#overlay").show();
});
$(document).ajaxComplete(function(){
    $("#overlay").hide();
});
$(document).ready(function(){

	$("#reference_doctor_id").prepend('<option></option>');
	$("#reference_doctor_id").prop('selectedIndex',0);

	if($("#sid").val().length==14)
		$("#datepicker").attr("disabled","disabled");
	
	$('#pid').change(function(){
		
		if($('#pid').val()!=""){
			var url = "{{ url('/patients/getPatient/') }}";
			$.ajax({
				type: "POST",
				data: { 'pid':$("#pid").val() },
				url: url,
				success: function (data) {
					if(data['success']=='true'){
						
						$('#hidden_pid').val($('#pid').val());
						$("#sid").val(data['data'][0].sid);
						p_name=data['data'][0].name.split(" ");
  						$("#fname").val(p_name[0]);$("#sname").val(p_name[1]);$("#mname").val(p_name[2]);$("#lname").val(p_name[3]);
						$("#gender").val(data['data'][0].gender);
						$("#address").val(data['data'][0].address);
						$("#datepicker").val(data['data'][0].birthdate)
						var birthdate = new Date($("#datepicker").val());
						var today = new Date();
						var diffYears = today.getFullYear() - birthdate.getFullYear(); 
						var diffMonths = today.getMonth() - birthdate.getMonth();
						var diffDays = today.getDate() - birthdate.getDate(); 
						
						if(isNaN(diffDays)){
							$("#age").val('');
							$("#err_msg").html('<b>رقم بطاقة المريض غير صحيح</b>');
							$("#err_msg").show();
							$("#submitButton").attr('disabled','true');
							return;	
						}
						if(diffDays < 0){
							diffMonths--;
							diffDays+=30;
						}
						if(isNaN(diffMonths)){
							$("#age").val('');
							$("#err_msg").html('<b>رقم بطاقة المريض غير صحيح</b>');
							$("#err_msg").show();
							$("#submitButton").attr('disabled','true');
							return;	
						}
						if(diffMonths < 0){
							diffMonths+=12;
							diffYears--;
						}
						var patient_age;
						if(diffYears > 0){
							patient_age=diffYears;
							if(diffYears >=11 )
								patient_age+=" سنه ";
							else
								patient_age+=" سنوات ";
						}
						else if(diffMonths > 0){
					
							patient_age=diffMonths;
							if(diffMonths >=11 )
								patient_age+=" شهر ";
							else
								patient_age+=" شهور ";
						}
						else if(diffDays > 0){
					
							patient_age=diffDays;
							if(diffDays >=11 )
								patient_age+=" يوم ";
							else
								patient_age+=" أيام ";
						}
						$("#age").val(patient_age);
						$("#job").val(data['data'][0].job);
						$("#social_status").val(data['data'][0].social_status);
						$("#phone_num").val(data['data'][0].phone_num);
						
						$("#pid").attr('disabled','true');
						$("#fname").attr('disabled','true');
						$("#sname").attr('disabled','true');
						$("#mname").attr('disabled','true');
						$("#lname").attr('disabled','true');
						$("#sid").attr('disabled','true');
						$("#gender").attr('disabled','true');
						$("#datepicker").attr('disabled','true');
					}
					else{
						$("#pid").val("");
						$('#hidden_pid').val("");
					}
				},
				error: function (data) {
					alert("Error");
				}
			});
		}
	});
    $('#sid').change(function(){
		if($('#sid').val().length == 14){
			var url = "{{ url('/patients/showSID/') }}";
			$.ajax({
				type: "POST",
				url: url,
				data: { 'sid':$("#sid").val() , 'checkflag': 'false' },
				success: function (data) {
					if(data['success']=='true'){
						$('#hidden_pid').val(data['data'][0].id);
						$("#pid").val(data['data'][0].id);
						p_name=data['data'][0].name.split(" ");
  						$("#fname").val(p_name[0]);$("#sname").val(p_name[1]);$("#mname").val(p_name[2]);$("#lname").val(p_name[3]);
						$("#gender").val(data['data'][0].gender);
						$("#address").val(data['data'][0].address);
						$("#datepicker").val(data['data'][0].birthdate)
						var birthdate = new Date($("#datepicker").val());
						var today = new Date();
						var diffYears = today.getFullYear() - birthdate.getFullYear(); 
						var diffMonths = today.getMonth() - birthdate.getMonth();
						var diffDays = today.getDate() - birthdate.getDate(); 
						
						if(isNaN(diffDays)){
							$("#age").val('');
							$("#err_msg").html('<b>رقم بطاقة المريض غير صحيح</b>');
							$("#err_msg").show();
							$("#submitButton").attr('disabled','true');
							return;	
						}
						if(diffDays < 0){
							diffMonths--;
							diffDays+=30;
						}
						if(isNaN(diffMonths)){
							$("#age").val('');
							$("#err_msg").html('<b>رقم بطاقة المريض غير صحيح</b>');
							$("#err_msg").show();
							$("#submitButton").attr('disabled','true');
							return;	
						}
						if(diffMonths < 0){
							diffMonths+=12;
							diffYears--;
						}
						var patient_age;
						if(diffYears > 0){
							patient_age=diffYears;
							if(diffYears >=11 )
								patient_age+=" سنه ";
							else
								patient_age+=" سنوات ";
						}
						else if(diffMonths > 0){
					
							patient_age=diffMonths;
							if(diffMonths >=11 )
								patient_age+=" شهر ";
							else
								patient_age+=" شهور ";
						}
						else if(diffDays > 0){
					
							patient_age=diffDays;
							if(diffDays >=11 )
								patient_age+=" يوم ";
							else
								patient_age+=" أيام ";
						}
						$("#age").val(patient_age);
						$("#job").val(data['data'][0].job);
						$("#social_status").val(data['data'][0].social_status);
						$("#phone_num").val(data['data'][0].phone_num);
						
						$("#pid").attr('disabled','true');
						$("#fname").attr('disabled','true');
						$("#sname").attr('disabled','true');
						$("#mname").attr('disabled','true');
						$("#lname").attr('disabled','true');
						$("#sid").attr('disabled','true');
						$("#gender").attr('disabled','true');
						$("#datepicker").attr('disabled','true');
					}
					else{
						$("#datepicker").attr("disabled","disabled");
						$("#submitButton").removeAttr('disabled');
						calculateBOD($('#sid').val());
						$("#pid").val("");
						$('#hidden_pid').val("");
						
					}
				},
				error: function (data) {
					alert("Error");
				}
			});
		
		}
		else if($('#sid').val().length == 0){
			$("#datepicker").removeAttr("disabled");
		}
	});
	
	$("#medical_id").change(function(){
		var url = "{{ url('/patients/getDepartmentDoctors/') }}";
		$.ajax({
			type: "POST",
			url: url,
			data: { 'mid':$("#medical_id").val() },
			success: function (data) {
				$("#reference_doctor_id").empty();
				$("#reference_doctor_id").prepend('<option></option>');
			  $("#reference_doctor_id").prop('selectedIndex',0);
				if(data['success']=='true'){
				
					for (i=0;i<data['data'].length;i++) {
						$("#reference_doctor_id").append("<option value='"+data['data'][i].id+"'>"+data['data'][i].name+"</option>");
					}
				}
			},
			error: function (data) {
				alert("Error");
			}
		});
	});
})
function calculateBOD(sid){
	var sid_string=sid;
	var prifx_year="";
	if(sid_string[0] == 2)
		prifx_year="19";
	else if(sid_string[0] == 3)
		prifx_year="20";
	else if(sid_string[0] == 4)
		prifx_year="21";
	else{
		$("#err_msg").html('رقم البطاقة غير صحيح');
		$("#err_msg").show();
		$("#sid").val("");
		$("#datepicker").removeAttr("disabled");
		return;
	}
		
	var year=prifx_year+""+sid_string[1]+""+sid_string[2];
	var month=sid_string[3]+""+sid_string[4];
	var day=sid_string[5]+""+sid_string[6];
	var date=year+"-"+month+"-"+day;
	
	var birthdate = new Date(date);
	var today = new Date();
	var diffYears = today.getFullYear() - birthdate.getFullYear(); 
	var diffMonths = today.getMonth() - birthdate.getMonth();
	var diffDays = today.getDate() - birthdate.getDate(); 
	
	if(isNaN(diffDays)){
		$("#age").val('');
		$("#err_msg").html('<b>رقم بطاقة المريض غير صحيح</b>');
		$("#err_msg").show();
		$("#submitButton").attr('disabled','true');
		return;	
	}
	if(diffDays < 0){
		diffMonths--;
		diffDays+=30;
	}
	if(isNaN(diffMonths)){
		$("#age").val('');
		$("#err_msg").html('<b>رقم بطاقة المريض غير صحيح</b>');
		$("#err_msg").show();
		$("#submitButton").attr('disabled','true');
		return;	
	}
	if(diffMonths < 0){
		diffMonths+=12;
		diffYears--;
	}

	$("#datepicker").val(date);
	$("#age").val(diffYears+" سنه -"+diffMonths+" شهر -"+diffDays+" يوم ");
}
// Function accepts numbers only
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
// Function limits the size of SIN field 
function isForteen(sid){
	if($("#"+sid).val().length >= 14)
		return false;
	return true;
}

function removeDisabled(){
	$('#fname').removeAttr('disabled');
	$('#lname').removeAttr('disabled');
	$('#mname').removeAttr('disabled');
	$('#sname').removeAttr('disabled');
	$('#pid').removeAttr('disabled');
	$('#sid').removeAttr('disabled');
	$('#gender').removeAttr('disabled');
	$('#datepicker').removeAttr('disabled');
}
</script>
@endsection
