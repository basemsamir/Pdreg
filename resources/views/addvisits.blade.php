@extends('layouts.app')
@section('title')
تحديث بيانات دخول مريض
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">تحديث بيانات دخول مريض</li>
      </ol>
	  <h1>
        بيانات دخول مريض
        <small>تحديث بيانات دخول مريض</small>
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

				<!-- /.box-header -->
				<!-- form start -->


				{!! Form::open(array('class'=>'form','method' => 'POST','name'=>'patient_form','id'=>'patient_form')) !!}
				  <div class="box-body">
					@if(Session::has('flash_message'))
						@if(Session::get('message_type') == 'false')
						<div class="alert alert-danger">
							<b>{{ Session::get('flash_message') }}</b>
						@else
						<div class="alert alert-success">
							<b>{{ Session::get('flash_message') }}</b>
							<br>
							@if(Session::has('vid'))
								<?php $var_id = Session::get('vid'); ?>
								<?php $pa_id = Session::get('id'); ?>
								لطباعة بطاقة دخول <a href='{{ url("/printpatientdata/$pa_id&$var_id") }}' target="_blank"> اضغط هنا </a>
							@endif
						@endif

						</div>
					@endif
					<div class="alert alert-danger" style="display: none"id="err_msg"></div>
					<div class="row">
						<div class="col-lg-4" style="float:right" >
							<div class="form-group" >
								{!! Form::label('كود المريض',null) !!}
								{!! Form::text('id',$data[0]->id,array('class'=>'form-control','id'=>'pid','disabled'=>'true','placeholder'=>'كود المريض','onkeypress'=>'return isNumber(event)')) !!}
							</div>
							<div class="form-group" >
							  {!! Form::label('الأسم',null) !!}
							  {!! Form::text('name',$data[0]->name,array('class'=>'form-control','disabled'=>'disabled')) !!}
							  {!! Form::hidden('code',$data[0]->id) !!}
							</div>
							<div class="form-group">
							   {!! Form::label('الرقم القومي',null) !!}
								{!! Form::number('p_sid',$data[0]->sid,array('class'=>'form-control','disabled'=>'disabled','placeholder'=>'الرقم القومي')) !!}
								{!! Form::hidden('patient_sid',$data[0]->sid,['id'=>'patient_sid']) !!}
								{!! Form::hidden('birthdate',null,['id'=>'birthdate']) !!}
								{!! Form::hidden('age',null,['id'=>'age']) !!}
							</div>
							<div class="form-group" >
							  {!! Form::label('العنوان',null) !!}
							  {!! Form::text('name',$data[0]->address,array('class'=>'form-control','disabled'=>'disabled')) !!}
							</div>
							<div class="form-group" >
							  {!! Form::label('السن',null) !!}
							    <?php
									$current_date = new DateTime();
									$birthdate = new DateTime($data[0]->birthdate);
									$interval = $current_date->diff($birthdate);
									$age="";
								?>
								@if($interval->y > 0)
								  <?php $age = $interval->y; ?>
								  @if( $interval->y > 10 )
									<?php $age.= " سنة"; ?>
								  @else
									<?php $age.= " سنوات"; ?>
								  @endif
								@elseif($interval->m > 0)
								   <?php $age.= $interval->m  ?>
								  @if( $interval->m > 10 )
									<?php  $age.= " شهر";  ?>
								  @else
									<?php  $age.= " شهور";  ?>
								  @endif
								@else
								  <?php  $age.= $interval->d  ?>
								  @if( $interval->d > 10 )
									<?php  $age.= " يوم" ; ?>
								  @else
									<?php  $age.= " أيام";  ?>
								  @endif
								@endif
							  {!! Form::text('name',$age,array('class'=>'form-control','disabled'=>'disabled')) !!}
							</div>
						</div>

						<div class="col-lg-8" style="float:right">
							<fieldset>
								<legend>بيانات المرافق</legend>
								<div class="col-lg-6" style="float:right">
									<div class="form-group @if ($errors->has('c_name')) has-error @endif">
									  {!! Form::label('الأسم',null,array('style'=>'color:red')) !!}
									  @if(isset($data[0]->c_name))
										{!! Form::text('c_name',$data[0]->c_name,array('class'=>'form-control','placeholder'=>'الاسم')) !!}
									  @else
										{!! Form::text('c_name',null,array('class'=>'form-control','placeholder'=>'الاسم')) !!}
									  @endif
									 {!! Form::hidden('v_code',$data[0]->vid) !!}
									  @if ($errors->has('c_name'))<span class="help-block">{{$errors->first('c_name')}}</span>@endif
									</div>
									<div class="form-group @if ($errors->has('sid')) has-error @endif">
									  {!! Form::label('رقم القومي',null) !!}
									   @if(isset($data[0]->c_sid))
										{!! Form::text('sid',$data[0]->c_sid,array('id'=>'eid','class'=>'form-control','placeholder'=>'الرقم القومي','onkeypress'=>'return isNumber(event)&&isForteen("eid")')) !!}
									   @else
										{!! Form::text('sid',null,array('id'=>'eid','class'=>'form-control','placeholder'=>'الرقم القومي','onkeypress'=>'return isNumber(event)&&isForteen("eid")')) !!}
									   @endif
									   @if ($errors->has('sid'))<span class="help-block">{{$errors->first('sid')}}</span>@endif
								    </div>

									<div class="form-group @if ($errors->has('relation_id')) has-error @endif">
									  {!! Form::label('درجة القرابة') !!}
									  {!! Form::select('relation_id',$relations,$data[0]->relation_id,['class' => 'form-control']); !!}
									  @if ($errors->has('relation_id'))<span class="help-block">{{$errors->first('relation_id')}}</span>@endif
									</div>

								</div>
								<div class="col-lg-6" style="float:right">

									<div class="form-group @if ($errors->has('address')) has-error @endif">
									  {!! Form::label('محل الاقامة') !!}
									  @if(isset($data[0]->vaddress))
										{!! Form::text('address',$data[0]->vaddress,array('class'=>'form-control','placeholder'=>'محل الاقامة')) !!}
									  @else
										{!! Form::text('address',null,array('class'=>'form-control','placeholder'=>'محل الاقامة')) !!}
									  @endif
									 @if ($errors->has('address'))<span class="help-block">{{$errors->first('address')}}</span>@endif
									</div>

									<div class="form-group @if ($errors->has('job')) has-error @endif">
									  {!! Form::label('المهنة') !!}
									  @if(isset($data[0]->vjob))
										{!! Form::text('job',$data[0]->vjob,array('class'=>'form-control','placeholder'=>'المهنة')) !!}
									  @else
										{!! Form::text('job',null,array('class'=>'form-control','placeholder'=>'المهنة')) !!}
									  @endif
									 @if ($errors->has('m_job'))<span class="help-block">{{$errors->first('job')}}</span>@endif
									</div>

									<div class="form-group @if ($errors->has('phone_num')) has-error @endif">
									  {!! Form::label('رقم التليفون') !!}
									  @if(isset($data[0]->vphone))
										{!! Form::text('phone_num',$data[0]->vphone,array('class'=>'form-control','placeholder'=>'رقم التليفون','onkeypress'=>'return isNumber(event)')) !!}
									  @else
										{!! Form::text('phone_num',null,array('class'=>'form-control','placeholder'=>'رقم التليفون','onkeypress'=>'return isNumber(event)')) !!}
									  @endif
									  @if ($errors->has('phone_num'))<span class="help-block">{{$errors->first('phone_num')}}</span>@endif
									</div>
								</div>
							</fieldset>
						</div>
					</div>

					<div class="box-header with-border">
					  <h3 class="box-title">تفاصيل اذن دخول المريض </h3>
					</div>
					<div class="row">
						<div class="col-lg-6" style="float:right">
						  <div class="form-group @if ($errors->has('entry')) has-error @endif"  >
							   @if(isset($data[0]->entry_id))
							    {!! Form::label('مكتب الدخول') !!}
								{!! Form::select('entry',$entrypoints,[$data[0]->entry_id],['class' => 'form-control','disabled'=>'disabled']); !!}
							   @else
								{!! Form::label('مكتب الدخول',null,array('style'=>'color:red')) !!}
								{!! Form::select('entry',$entrypoints,null,['class' => 'form-control']); !!}
								@endif
								@if ($errors->has('entry'))<span class="help-block">{{$errors->first('entry')}}</span>@endif
							</div>
							<div class="form-group @if ($errors->has('medical_id')) has-error @endif"  >
							   @if(isset($data[0]->dept_id))
							    {!! Form::label('أسم القسم') !!}
								{!! Form::select('medical_id',$medicalunits,[$data[0]->dept_id],['class' => 'form-control','disabled'=>'disabled']); !!}
							   @else
							    {!! Form::label('أسم القسم',null,array('style'=>'color:red')) !!}
								{!! Form::select('medical_id',$medicalunits,null,['id'=>'medical_id','class' => 'form-control']); !!}
							   @endif
							   @if ($errors->has('medical_id'))<span class="help-block">{{$errors->first('medical_id')}}</span>@endif
							</div>
						  <div class="form-group @if($errors->has('entry_date')) has-error @endif">

							@if(isset($data[0]->entry_date))
                {!! Form::label('تاريخ الدخول',null) !!}
                @if($sub_type_entrypoint == "entry_and_exit")
							    {!! Form::text('entry_date',$data[0]->entry_date,array('class'=>'form-control','id'=>'datepicker2')) !!}
                @else
                  {!! Form::text('entry_date',$data[0]->entry_date,array('class'=>'form-control','id'=>'datepicker2','disabled')) !!}
                @endif
              @else
                {!! Form::label('تاريخ الدخول',null,array('style'=>'color:red')) !!}
							  {!! Form::text('entry_date',null,array('class'=>'form-control','id'=>'datepicker2','placeholder'=>'1900-01-01')) !!}
							@endif
							@if($errors->has('entry_date'))<span class="help-block">{{$errors->first('entry_date')}}</span>@endif
						  </div>
						  <div class="bootstrap-timepicker">
							  <div class="form-group @if($errors->has('entry_time')) has-error @endif">
								@if(isset($data[0]->entry_time))
								  {!! Form::label('entry_time','ساعة الدخول') !!}
                  @if($sub_type_entrypoint == "entry_and_exit")
								     {!! Form::text('entry_time',$data[0]->entry_time,array('class'=>'form-control timepicker')) !!}
                  @else
                     {!! Form::text('entry_time',$data[0]->entry_time,array('class'=>'form-control timepicker','disabled')) !!}
                  @endif
								@else
								  {!! Form::label('entry_time','ساعة الدخول',array('style'=>'color:red')) !!}
								  {!! Form::text('entry_time',null,array('class'=>'form-control timepicker')) !!}
								@endif
    							  @if ($errors->has('entry_time'))<span class="help-block">{{$errors->first('entry_time')}}</span>@endif
							  </div>
						  </div>
						  <div class="form-group @if($errors->has('reference_doctor_id')) has-error @endif " >
							{!! Form::label('الطبيب المعالج') !!}
							@if(isset($data[0]->reference_doctor_id))
							   {!! Form::select('reference_doctor_id',$first_department_doctors,$data[0]->doctor_name,['id'=>'reference_doctor_id','class' => 'form-control','disabled']); !!}
						    @else
								{!! Form::select('reference_doctor_id',$first_department_doctors,null,['id'=>'reference_doctor_id','class' => 'form-control']); !!}
							@endif
						    @if ($errors->has('reference_doctor_id'))<span class="help-block">{{$errors->first('reference_doctor_id')}}</span>@endif
						  </div>
						</div>
						<div class="col-lg-6" style="float:right">
						  <div class="form-group @if($errors->has('room_number')) has-error @endif">
							  {!! Form::label('room_number','رقم الغرفة') !!}
							  @if(isset($data[0]->room_number))
								{!! Form::text('room_number',$data[0]->room_number,array('class'=>'form-control','placeholder'=>'رقم الغرفة')) !!}
							  @else
								{!! Form::text('room_number',null,array('class'=>'form-control','placeholder'=>'رقم الغرفة')) !!}
							  @endif
							  @if ($errors->has('room_number'))<span class="help-block">{{$errors->first('room_number')}}</span>@endif
						  </div>
						  <div class="form-group @if($errors->has('file_number')) has-error @endif">
							  {!! Form::label('file_number','رقم الملف',array('style'=>'color:red')) !!}
							  @if(isset($data[0]->file_number))
								{!! Form::text('file_number',$data[0]->file_number,array('class'=>'form-control','placeholder'=>'رقم الملف')) !!}
							  @else
								{!! Form::text('file_number',null,array('class'=>'form-control','placeholder'=>'رقم الملف')) !!}
							  @endif
							  @if ($errors->has('file_number'))<span class="help-block">{{$errors->first('file_number')}}</span>@endif
						  </div>
						  <div class="form-group @if($errors->has('cure_type_id')) has-error @endif">
						      {!! Form::label('نوع العلاج') !!}
							  @if(isset($data[0]->cure_type_id))
								{!! Form::select('cure_type_id',$cure_types,$data[0]->cure_type_id,['class' => 'form-control']); !!}
							  @else
								{!! Form::select('cure_type_id',$cure_types,null,['class' => 'form-control']); !!}
							  @endif
						  </div>
						  <div class="form-group @if($errors->has('contract')) has-error @endif">
							  {!! Form::label('contract','جهة التعاقد') !!}
							  @if(isset($data[0]->cure_type_id))
							  {!! Form::text('contract',$data[0]->contract,array('class'=>'form-control','placeholder'=>'جهة التعاقد')) !!}
							  @else
							  {!! Form::text('contract',null,array('class'=>'form-control','placeholder'=>'جهة التعاقد')) !!}
							  @endif
							  @if ($errors->has('contract'))<span class="help-block">{{$errors->first('contract')}}</span>@endif
						  </div>
						  <div class="form-group @if($errors->has('converted_by_doctor')) has-error @endif">
							  {!! Form::label('محول بواسطة',null) !!}
							  @if(isset($data[0]->converted_by_doctor))
							  {!! Form::text('converted_by_doctor',$data[0]->converted_by_doctor,array('class'=>'form-control','placeholder'=>'محول بواسطة')) !!}
							  @else
							  {!! Form::text('converted_by_doctor',null,array('class'=>'form-control','placeholder'=>'محول بواسطة')) !!}
							  @endif
							  @if ($errors->has('converted_by_doctor'))<span class="help-block">{{$errors->first('converted_by_doctor')}}</span>@endif
						  </div>
						</div>

					</div>

					</div>
					<!-- /.box-body -->

				  <div class="box-footer">
					<button type="button" class="btn btn-primary" onclick="$('#patient_form').submit();" >@if($data[0]->vid !="") تحديث @else تسجيل @endif</button>
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

	 $("#medical_id").change(function(){
		var url = "{{ url('/patients/getDepartmentDoctors/') }}";
		$.ajax({
			type: "POST",
			url: url,
			data: { 'mid':$("#medical_id").val() },
			success: function (data) {
				$("#reference_doctor_id").empty();
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
});
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
		$("#err_msg").html('<b>رقم بطاقة المريض غير صحيح</b>');
		$("#err_msg").show();
		$("#pid").val("");
	}

	var year=prifx_year+""+sid_string[1]+""+sid_string[2];
	var month=sid_string[3]+""+sid_string[4];
	var day=sid_string[5]+""+sid_string[6];
	var date=year+"-"+month+"-"+day;
	$("#birthdate").val(date);
	var birthdate = new Date(date);
	var today = new Date();
	var diffYears = today.getFullYear() - birthdate.getFullYear();

	if(isNaN(diffYears) || diffYears < 0){
		$("#err_msg").html('<b>رقم بطاقة المريض غير صحيح</b>');
		$("#err_msg").show();
		$("#patient_form button").attr('disabled',true);
		return;
	}
	else{
		$("#err_msg").hide();
		$("#patient_form button").attr('disabled',false);
		$("#age").val(diffYears);
	}
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
</script>
@endsection
