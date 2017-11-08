@extends('layouts.app')
@section('title')
حجز تذكرة كشف عيادة
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">حجز تذكرة كشف عيادة</li>
      </ol>
	  <h1>
        بيانات المريض
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
				{!! Form::open(array('class'=>'form','name'=>'patient_form','id'=>'patient_form','onsubmit'=>'return removeDisabled()')) !!}
				  <div class="box-body">

					@if(Session::has('flash_message'))
						@if(Session::get('message_type')=='false')
							<div class="alert alert-danger">
						@else
							<div class="alert alert-success">
						@endif
								<b>{{ Session::get('flash_message') }} </b>
								<br>
								<?php $e=Session::get('vid'); ?>
								@if($e!="")
									لطباعة تذكرة المريض <a href='{{ url("patients/ticket/$e") }}' target="_blank"> اضغط هنا </a>
								@endif
							</div>
					@endif
					<div class="alert alert-danger" style="display: none" id="err_msg"></div>
					<div class="row">
						<div class="col-lg-4" style="float:right">
							@if ($errors->has('id'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('كود المريض',null) !!}
							  @if(isset($patient_data))
								{!! Form::text('id',$patient_data['id'],array('disabled','class'=>'form-control','id'=>'pid','placeholder'=>'كود المريض','onkeypress'=>'return isNumber(event)')) !!}
							  @else
								{!! Form::text('id',null,array('class'=>'form-control','id'=>'pid','placeholder'=>'كود المريض','onkeypress'=>'return isNumber(event)')) !!}
							  @endif
							  @if ($errors->has('id'))<span class="help-block">{{$errors->first('id')}}</span>@endif
							</div>
							@if($errors->has('sid'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('رقم البطاقة',null) !!}
							  @if(isset($patient_data))
								{!! Form::text('sid',$patient_data['sid'],array('size'=>'14','class'=>'form-control','id'=>'sid','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("sid")','disabled')) !!}
                {!! Form::hidden('hidden_sid',$patient_data['sid']) !!}
                @else
								{!! Form::text('sid',null,array('size'=>'14','class'=>'form-control','id'=>'sid','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("sid")')) !!}
                {!! Form::hidden('hidden_sid',null,array('id'=>'hidden_sid')) !!}
                @endif
							  @if ($errors->has('sid'))<span class="help-block">{{$errors->first('sid')}}</span>@endif
							</div>
							@if ($errors->has('ticket_num'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('رقم التذكرة',null,array('style'=>'color:red')) !!}
								{!! Form::text('ticket_num',null,array('class'=>'form-control','id'=>'ticket_num','placeholder'=>'رقم التذكرة','onkeypress'=>'return isNumber(event)')) !!}
							  @if ($errors->has('ticket_num'))<span class="help-block">{{$errors->first('ticket_num')}}</span>@endif
							</div>
							@if ($errors->has('fname') || $errors->has('sname') || $errors->has('mname') || $errors->has('lname'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('الأسم',null,array('style'=>'color:red')) !!} <br>
							  @if(isset($patient_data))
								<?php $name_arr=explode(" ",$patient_data['name']);?>
									{!! Form::text('fname',$name_arr[0],array('class'=>'form-control','disabled','id'=>'fname','style'=>'width:24%;display:inline')) !!}
									{!! Form::text('sname',$name_arr[1],array('class'=>'form-control','disabled','id'=>'sname','style'=>'width:24%;display:inline')) !!}
									{!! Form::text('mname',$name_arr[2],array('class'=>'form-control','disabled','id'=>'mname','style'=>'width:24%;display:inline')) !!}
									{!! Form::text('lname',$name_arr[3],array('class'=>'form-control','disabled','id'=>'lname','style'=>'width:24%;display:inline')) !!}
							  @else
									{!! Form::text('fname',null,array('class'=>'form-control','id'=>'fname','style'=>'width:24%;display:inline')) !!}
									{!! Form::text('sname',null,array('class'=>'form-control','id'=>'sname','style'=>'width:24%;display:inline')) !!}
									{!! Form::text('mname',null,array('class'=>'form-control','id'=>'mname','style'=>'width:24%;display:inline')) !!}
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
							@if ($errors->has('gender'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('النوع',null,array('style'=>'color:red')) !!}
							  @if(isset($patient_data))
								{!! Form::select('gender',[''=>'أختر النوع','M' => 'ذكر', 'F' => 'أنثى'], $patient_data['gender'],['class'=>'form-control','disabled','id'=>'gender_select']); !!}
							  @else
								{!! Form::select('gender',[''=>'أختر النوع','M' => 'ذكر', 'F' => 'أنثى'], '',['class'=>'form-control','id'=>'gender_select']); !!}
							  @endif
							  @if ($errors->has('gender'))<span class="help-block">{{$errors->first('gender')}}</span>@endif
							</div>
						</div>
						<div class="col-lg-4" style="float:right">
							@if($errors->has('year_age'))
								<div class="form-group has-error">
							@else

								<div class="form-group">
							@endif
							  {!! Form::label('العمر (عدد الأيام / عدد الأشهر / عدد السنين )',null,array('style'=>'color:red')) !!} <br>
							  @if(isset($patient_data))
								<?php
									  $current_date = new DateTime();
									  $birthdate = new DateTime($patient_data['birthdate']);
									  $interval = $current_date->diff($birthdate);
								?>
                {!! Form::select('day_age',$days,$interval->d,['class'=>'form-control','disabled'=>'disabled','id'=>'day_age','style'=>'width:29%;display:inline']) !!}
								{!! Form::select('month_age',$ages,$interval->m,['class'=>'form-control','disabled'=>'disabled','style'=>'width:39%;display:inline']); !!}
								{!! Form::text('year_age',$interval->y,array('class'=>'form-control','id'=>'year_age','disabled'=>'disabled','style'=>'width:29%;display:inline')) !!}
							  @else
                {!! Form::select('day_age',$days,null,['class'=>'form-control','id'=>'day_age','style'=>'width:29%;display:inline']) !!}
								{!! Form::select('month_age',$ages,null,['class'=>'form-control','id'=>'month_age','style'=>'width:39%;display:inline']); !!}
								{!! Form::text('year_age',null,array('placeholder'=>'عدد السنين','class'=>'form-control','id'=>'year_age','onkeypress'=>'return isNumber(event)','style'=>'width:29%;display:inline')) !!}
							  @endif
							  @if($errors->has('year_age'))<span class="help-block">{{$errors->first('year_age')}}</span>@endif
							</div>
							@if ($errors->has('address'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('العنوان',null,array('style'=>'color:red')) !!}
							  @if(isset($patient_data))
								{!! Form::text('address',$patient_data['address'],array('disabled','class'=>'form-control','id'=>'address')) !!}
							  @else
							  {!! Form::text('address',null,array('id'=>'address','class'=>'form-control','placeholder'=>'العنوان')) !!}
							  @endif
							  @if ($errors->has('address'))<span class="help-block">{{$errors->first('address')}}</span>@endif
							</div>
              @if ($errors->has('entry'))
                <div class="form-group has-error">
              @else
  							<div class="form-group"  >
              @endif
  							   {!! Form::label('مكتب حجز التذاكر',null,array('style'=>'color:red')) !!}
  							   {!! Form::select('entry',$entrypoints,null,['class' => 'form-control']); !!}
              @if ($errors->has('entry'))<span class="help-block">{{$errors->first('entry')}}</span>@endif

                </div>
              @if ($errors->has('medical_id'))
                <div class="form-group has-error">
              @else
  							<div class="form-group"  >
              @endif
							   {!! Form::label('أسم العيادة',null,array('style'=>'color:red')) !!}
							   {!! Form::select('medical_id',$medical_units,null,['class' => 'form-control']); !!}
              @if ($errors->has('medical_id'))<span class="help-block">{{$errors->first('medical_id')}}</span>@endif

							</div>
				  @if ($errors->has('reservation_type'))
					<div class="form-group has-error">
				  @else
					<div class="form-group"  >
				  @endif
				   {!! Form::label('نوع الحجز',null,array('style'=>'color:red')) !!}
				   {!! Form::select('reservation_type',['T' => 'تذكرة فقط', 'T&E' => 'تذكرة و دخول'],'',['class' => 'form-control','onchange'=>'show_hideCompanionDiv();','id'=>'reservation_type']); !!}
				   @if ($errors->has('reservation_type'))<span class="help-block">{{$errors->first('reservation_type')}}</span>@endif

					</div>
			</div>

            <div class="col-lg-4" style="float:right;display:none" id="companionDiv">
              <fieldset>
                <legend>بيانات المرافق</legend>
                @if ($errors->has('c_name'))
  								<div class="form-group has-error">
  							@else
  								<div class="form-group">
  							@endif
  							  {!! Form::label('أسم المرافق',null,array('style'=>'color:red')) !!}
  							  {!! Form::text('c_name',null,array('class'=>'form-control','placeholder'=>'الاسم')) !!}

  							  @if ($errors->has('c_name'))<span class="help-block">{{$errors->first('c_name')}}</span>@endif
  							  </div>
                @if ($errors->has('relation_id'))
  								<div class="form-group has-error">
  							@else
  								<div class="form-group">
  							@endif
  							  {!! Form::label('درجة القرابة',null,array('style'=>'color:red')) !!}
  							  {!! Form::select('relation_id',$relations,null,['class' => 'form-control']); !!}
  							  @if ($errors->has('relation_id'))<span class="help-block">{{$errors->first('relation_id')}}</span>@endif
  							  </div>
                @if ($errors->has('c_address'))
  								<div class="form-group has-error">
  							@else
  								<div class="form-group">
  							@endif
  							  {!! Form::label('العنوان',null,array('style'=>'color:red')) !!}
  							  {!! Form::text('c_address',null,array('id'=>'address','class'=>'form-control','placeholder'=>'العنوان')) !!}
  							  @if ($errors->has('c_address'))<span class="help-block">{{$errors->first('c_address')}}</span>@endif
  							</div>
                @if ($errors->has('job'))
                  <div class="form-group has-error">
                @else
                  <div class="form-group">
                @endif
                  {!! Form::label('المهنة') !!}
                  {!! Form::text('job',null,array('id'=>'address','class'=>'form-control','placeholder'=>'العنوان')) !!}
                  @if ($errors->has('job'))<span class="help-block">{{$errors->first('job')}}</span>@endif
                </div>
  							@if ($errors->has('c_sid'))
  								<div class="form-group has-error">
  							@else
  								<div class="form-group">
  							@endif
  							  {!! Form::label('رقم البطاقة',null,array('style'=>'color:red')) !!}
  							  {!! Form::text('c_sid',null,array('class'=>'form-control','id'=>'c_sid','placeholder'=>'الرقم القومي','onkeypress'=>'return isNumber(event)&&isForteen("c_sid")')) !!}
  							  @if ($errors->has('c_sid'))<span class="help-block">{{$errors->first('c_sid')}}</span>@endif
  							  </div>
                  <div class="bootstrap-timepicker">
                  @if ($errors->has('entry_time'))
    								<div class="form-group has-error">
    							@else
    								<div class="form-group">
    							@endif
    							  {!! Form::label('وقت الدخول',null,array('style'=>'color:red')) !!}
    							  {!! Form::text('entry_time',null,array('class'=>'form-control timepicker')) !!}
    							  @if ($errors->has('entry_time'))<span class="help-block">{{$errors->first('entry_time')}}</span>@endif
    							  </div>
                  </div> <!-- bootstrap-timepicker -->
                  @if ($errors->has('entry_reason_desc'))
    								<div class="form-group has-error">
    							@else
    								<div class="form-group">
    							@endif
    							  {!! Form::label('سبب الدخول',null,array('style'=>'color:red')) !!}
    							  {!! Form::textarea('entry_reason_desc',null,array('class'=>'form-control','id'=>'entry_reason_desc','rows'=>'2')) !!}
    							  @if ($errors->has('entry_reason_desc'))<span class="help-block">{{$errors->first('entry_reason_desc')}}</span>@endif
    							  </div>
              </fieldset>
            </div>
					</div>
				  </div>
				  <!-- /.box-body -->

				  <div class="box-footer">
					<button type="button" class="btn btn-primary" onclick="$('#patient_form').submit();" >تسجيل</button>
					<input type="reset" class="btn btn-success" value="جديد" onclick="$('#err_msg').hide();$('.form input').removeAttr('disabled');$('#gender_select').removeAttr('disabled');
					$('#month_age').removeAttr('disabled');$('#day_age').removeAttr('disabled');$('.alert').hide();" />
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

   $('#ticket_num').focus();
  if($("#reservation_type").val() == "T&E")
    $("#companionDiv").show();
  else {
    $("#companionDiv").hide();
  }
  $('#sid').on('change paste',function(){
		$("#err_msg").hide();
  		if($('#sid').val().length == 14){
        $("#hidden_sid").val($('#sid').val());
  			var url = "{{ url('/patients/showSID/') }}";
  			$.ajax({
  				type: "POST",
  				url: url,
  				data: { 'sid':$("#sid").val() , 'checkflag': 'false' },
  				success: function (data) {
  					if(data['success']=='true'){

  						p_name=data['data'][0].name.split(" ");
  						$("#fname").val(p_name[0]).attr("disabled", true);$("#sname").val(p_name[1]).attr("disabled", true);$("#mname").val(p_name[2]).attr("disabled", true);$("#lname").val(p_name[3]).attr("disabled", true);
  						var birthdate = new Date(data['data'][0].birthdate);
  						var today = new Date();
  						var diffYears = today.getFullYear() - birthdate.getFullYear();
  						var diffMonths = today.getMonth() - birthdate.getMonth();
  						var diffDays = today.getDate() - birthdate.getDate();
  						if(diffMonths < 0){
  							diffMonths+=12;
  							diffYears--;
  						}
						  if(diffDays < 0){
							diffMonths--;
							diffDays+=30;
						  }
  						$("#year_age").val(diffYears).attr("disabled", true);
  						$("#month_age option[value='"+diffMonths+"']").prop('selected',true);
						$("#day_age option[value='"+diffDays+"']").prop('selected',true);
  						if(data['data'][0].gender == "F")
  							$("#gender_select option[value='F']").prop('selected',true);
  						else
  							$("#gender_select option[value='M']").prop('selected',true);
  						$("#pid").val(data['data'][0].id).attr("disabled", true);
  						$("#sid").attr("disabled", true);
  						$("#address").val(data['data'][0].address).attr("disabled", true);
  						$("#gender_select").attr("disabled", true);
  						$("#month_age").attr("disabled", true);
						$("#day_age").attr("disabled", true);
  					}
  					else{
  						$("#pid").val("");
  						calculateBOD($("#sid").val());
  					}
  				},
  				error: function (data) {
  					alert("Error");
  				}
  			});

  		}
  	});
	$('#pid').change(function(){
		var url = "{{ url('/patients/showPID/') }}";
		$.ajax({
			type: "POST",
			data: { 'pid':$("#pid").val() },
			url: url,
			success: function (data) {
				if(data['success']=='true'){
					p_name=data['data'][0].name.split(" ");
						$("#fname").val(p_name[0]).attr('disabled',true);$("#sname").val(p_name[1]).attr('disabled',true);$("#mname").val(p_name[2]).attr('disabled',true);$("#lname").val(p_name[3]).attr('disabled',true);
						var birthdate = new Date(data['data'][0].birthdate);
						var today = new Date();
						var diffYears = today.getFullYear() - birthdate.getFullYear();
						var diffMonths = today.getMonth() - birthdate.getMonth();
						var diffDays = today.getDate() - birthdate.getDate();
						if(diffMonths < 0){
							diffMonths+=12;
							diffYears--;
						}
						if(diffDays < 0){
						  diffMonths--;
						  diffDays+=30;
						}
						$("#year_age").val(diffYears).attr('disabled',true);
						$("#month_age option[value='"+diffMonths+"']").prop('selected',true);
						$("#day_age option[value='"+diffDays+"']").prop('selected',true);
						if(data['data'][0].gender == "F")
							$("#gender_select option[value='F']").prop('selected',true);
						else
							$("#gender_select option[value='M']").prop('selected',true);
						$("#sid").val(data['data'][0].sid).attr('disabled',true);
						$("#pid").attr('disabled',true);
						$("#address").val(data['data'][0].address).attr('disabled',true);
						$("#gender_select").attr("disabled", true);
						$("#month_age").attr("disabled", true);
						$("#day_age").attr("disabled", true);

				}
				else{
					document.getElementById("patient_form").reset();
				}
			},
			 error: function (request, status, error) {
				alert("Error");
			}
		});

	});
});

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
// Function calculates the age field
function calculateBOD(sid){
	var sid_string=sid;
	var prifx_year="";
	if(sid_string[0] == 2)
		prifx_year="19";
	else if(sid_string[0] == 3)
		prifx_year="20";
	else{
		$("#err_msg").html('الرقم القومي غير صحيح');
		$("#err_msg").show();
		$("#sid").val("");
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
	if(diffMonths < 0){
		diffMonths+=12;
		diffYears--;
	}
	if(diffDays < 0){
		diffMonths--;
		diffDays+=30;
	}
	if( isNaN(diffYears) || diffYears < 0 || isNaN(diffMonths) || isNaN(diffDays) ){
		$("#err_msg").html('الرقم القومي غير صحيح');
		$("#err_msg").show();
		return;
	}
	else{
		$("#year_age").val(diffYears);
		$("#month_age").val(diffMonths);
    $("#day_age").val(diffDays);
	}
}
function removeDisabled(){
	$('#age').removeAttr('disabled');
	$('#pid').removeAttr('disabled');
}
function show_hideCompanionDiv(e){

  if($("#reservation_type").val() == "T&E")
    $("#companionDiv").show();
  else {
    $("#companionDiv").hide();
  }
}
</script>
@endsection
