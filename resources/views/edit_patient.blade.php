@extends('layouts.app')
@section('title')
تعديل بيانات المريض
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">تعديل بيانات المريض</li>
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
				@if(isset($patient_data))
				{!! Form::open(array('class'=>'form','method'  => 'patch','action' => ['AdminController@updatePatient',$patient_data['id'],$vid],'id'=>'patient_form')) !!}
				@else
				{!! Form::open(array('class'=>'form','method'  => 'patch','action' => ['AdminController@updatePatient',$visit[0]->patient->id,$vid],'id'=>'patient_form')) !!}
				
				@endif
					<div class="box-body">

					@if(Session::has('success'))
						<div class="alert alert-success">
							<b>{{ Session::get('success') }} </b>
						</div>
					@endif
					<div class="alert alert-danger" style="display: none" id="err_msg"></div>
					<div class="row">
						<div class="col-lg-6" >

              @if(!is_null($visit))
								@if(isset($visit[0]->ticket_type))
								<div class="form-group">
									{!! Form::label('حالة التذكرة',null,array('style'=>'color:red')) !!}
									{!! Form::select('ticket_status',['T'=>'عادي','F' => 'مجاني'], $visit[0]->ticket_status,['class'=>'form-control','id'=>'ticket_status_select']); !!}
								</div>
								<div class="form-group @if($errors->has('ticket_type'))  has-error @endif">
										{!! Form::label('نوع التذكرة',null,array('style'=>'color:red')) !!}
										{!! Form::select('ticket_type',['G' => 'استقبال عام', 'T' => 'استقبال اصابات'], $visit[0]->ticket_type,['class'=>'form-control','id'=>'ticket_type_select']) !!}
										@if ($errors->has('ticket_type'))<span class="help-block">{{$errors->first('ticket_type')}}</span>@endif
								</div>
								@endif
                <div class="form-group @if($errors->has('ticket_number')) has-error @endif ">
                  {!! Form::label('رقم التذكرة',null) !!}
                  {!! Form::text('ticket_number',$visit[0]->ticket_number,array('id'=>'ticket_number','class'=>'form-control','onkeypress'=>'return isNumber(event)')) !!}
                  @if ($errors->has('ticket_number'))<span class="help-block">{{$errors->first('ticket_number')}}</span>@endif
                  {!! Form::hidden('ticket_type',$visit[0]->ticket_type) !!}
                </div>
								
								@if(isset($visit[0]->ticket_type))
										<div class="form-group @if($errors->has('serial_number')) has-error @endif">
											{!! Form::label('رقم التسلسل',null,array('style'=>'color:red')) !!}
											{!! Form::text('serial_number',$visit[0]->serial_number,array('class'=>'form-control','placeholder'=>'رقم التسلسل','onkeypress'=>'return isNumber(event)')) !!}
										@if ($errors->has('serial_number'))<span class="help-block">{{$errors->first('serial_number')}}</span>@endif
									
										</div>
										<div class="form-group @if($errors->has('reg_date')) has-error @endif">
											{!! Form::label('تاريخ التسجيل',null,array('style'=>'color:red')) !!}
											{!! Form::text('reg_date',\Carbon\Carbon::parse($visit[0]->registration_datetime)->format('Y-m-d'),array('class'=>'form-control','id'=>'datepicker','placeholder'=>'1900-01-01')) !!}
											@if ($errors->has('reg_date'))<span class="help-block">{{$errors->first('reg_date')}}</span>@endif
										</div>
										<div class="bootstrap-timepicker">
											<div class="form-group @if($errors->has('reg_time')) has-error @endif">
												{!! Form::label('reg_time','ساعة التسجيل',array('style'=>'color:red')) !!}
												{!! Form::text('reg_time',\Carbon\Carbon::parse($visit[0]->registration_datetime)->format('g:i A'),array('class'=>'form-control timepicker')) !!}
												@if ($errors->has('reg_time'))<span class="help-block">{{$errors->first('reg_time')}}</span>@endif
										
											</div>
										</div>
										
										<div class="form-group">
											{!! Form::label('المشاهدة',null) !!}
											{!! Form::text('watching_status',$visit[0]->watching_status,array('class'=>'form-control','placeholder'=>'المشاهدة')) !!}
										</div>
										
										<div class="form-group @if ($errors->has('sent_by_person')) has-error @endif">
									{!! Form::label('مرسل بمعرفة',null,array('style'=>'color:red')) !!}
									{!! Form::textarea('sent_by_person',$visit[0]->sent_by_person,array('class'=>'form-control','rows'=>5,'cols'=>4)) !!}
									@if ($errors->has('sent_by_person'))<span class="help-block">{{$errors->first('sent_by_person')}}</span>@endif
								
								</div>
								<div class="form-group @if ($errors->has('ticket_companion_name')) has-error @endif">
									{!! Form::label('أسم مرافق الحجز',null) !!}
									{!! Form::text('ticket_companion_name',$visit[0]->ticket_companion_name,array('class'=>'form-control','placeholder'=>'أسم مرافق الحجز')) !!}
									@if ($errors->has('ticket_companion_name'))<span class="help-block">{{$errors->first('ticket_companion_name')}}</span>@endif
								
								</div>
								<div class="form-group  @if ($errors->has('ticket_companion_sin')) has-error @endif">
									{!! Form::label('رقم البطاقة مرافق الحجز',null) !!}
									{!! Form::text('ticket_companion_sin',$visit[0]->ticket_companion_sin,array('class'=>'form-control','placeholder'=>'رقم البطاقة مرافق الحجز','onkeypress'=>'return isNumber(event)&&isForteen(this)')) !!}
									@if ($errors->has('ticket_companion_sin'))<span class="help-block">{{$errors->first('ticket_companion_sin')}}</span>@endif
								</div>
								@endif
              @endif
							
						</div>
						<div class="col-lg-6" >
							<div class="form-group 	@if($errors->has('sid')) has-error @endif">
							  {!! Form::label('رقم البطاقة',null) !!}
								@if(isset($patient_data))
							  	{!! Form::text('sid',$patient_data['sid'],array('size'=>'14','class'=>'form-control','id'=>'sid','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("sid")')) !!}
							  @else
									{!! Form::text('sid',$visit[0]->patient->sid,array('size'=>'14','class'=>'form-control','id'=>'sid','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("sid")')) !!}
								@endif
								@if ($errors->has('sid'))<span class="help-block">{{$errors->first('sid')}}</span>@endif
							</div>
						  <div class="form-group @if ($errors->has('fname') || $errors->has('sname') || $errors->has('mname') || $errors->has('lname'))
						 	has-error @endif">
							  {!! Form::label('الأسم',null,array('style'=>'color:red')) !!} <br>
							  <?php if(isset($patient_data))
												$name_arr=explode(" ",$patient_data['name']);
											else
												$name_arr=explode(" ",$visit[0]->patient->name);
								?>
								{!! Form::text('fname',$name_arr[0],array('class'=>'form-control','id'=>'fname','style'=>'width:24%;display:inline')) !!}
								{!! Form::text('sname',$name_arr[1],array('class'=>'form-control','id'=>'sname','style'=>'width:24%;display:inline')) !!}
								{!! Form::text('mname',$name_arr[2],array('class'=>'form-control','id'=>'mname','style'=>'width:24%;display:inline')) !!}
								{!! Form::text('lname',$name_arr[3],array('class'=>'form-control','id'=>'lname','style'=>'width:24%;display:inline')) !!}
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
							<div class="form-group @if ($errors->has('gender')) has-error @endif ">
							  {!! Form::label('النوع',null,array('style'=>'color:red')) !!}
								@if(isset($patient_data))
							  	{!! Form::select('gender',[''=>'أختر النوع','M' => 'ذكر', 'F' => 'أنثى'], $patient_data['gender'],['class'=>'form-control','id'=>'gender_select']); !!}
							  @else
									{!! Form::select('gender',[''=>'أختر النوع','M' => 'ذكر', 'F' => 'أنثى'], $visit[0]->patient->gender,['class'=>'form-control','id'=>'gender_select']); !!}
							 @endif
								@if ($errors->has('gender'))<span class="help-block">{{$errors->first('gender')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('year_age')) has-error @endif">
                {!! Form::label('العمر (عدد الأيام / عدد الأشهر / عدد السنين )',null,array('style'=>'color:red')) !!} <br>
                <?php
									  $current_date = new DateTime();
										if(isset($patient_data))
									  	$birthdate = new DateTime($patient_data['birthdate']);
										else
											$birthdate = new DateTime($visit[0]->patient->birthdate);
									  $interval = $current_date->diff($birthdate);
								?>
                {!! Form::select('day_age',$days,$interval->d,['class'=>'form-control','id'=>'day_age','style'=>'width:29%;display:inline']) !!}
								{!! Form::select('month_age',$ages,$interval->m,['class'=>'form-control','id'=>'month_age','style'=>'width:39%;display:inline']); !!}
								{!! Form::text('year_age',$interval->y,array('class'=>'form-control','id'=>'year_age','onkeypress'=>'return isNumber(event)','style'=>'width:29%;display:inline')) !!}
							  @if($errors->has('year_age'))<span class="help-block">{{$errors->first('year_age')}}</span>@endif
							</div>
						
							<div class="form-group 	@if ($errors->has('address')) has-error @endif">

							  {!! Form::label('العنوان',null,array('style'=>'color:red')) !!}
								@if(isset($patient_data))
							  	{!! Form::text('address',$patient_data['address'],array('class'=>'form-control','id'=>'address')) !!}
							  @else
								 {!! Form::text('address',$visit[0]->patient->address,array('class'=>'form-control','id'=>'address')) !!}
							 	@endif
								@if ($errors->has('address'))<span class="help-block">{{$errors->first('address')}}</span>@endif
							</div>
							@if(isset($visit[0]->ticket_type))
								<div class="form-group @if ($errors->has('job')) has-error @endif">
									{!! Form::label('المهنة',null,array('style'=>'color:red')) !!}
									@if(isset($patient_data))
									{!! Form::text('job',$patient_data['job'],array('id'=>'job','disabled','class'=>'form-control','placeholder'=>'المهنة')) !!}
									@else
										{!! Form::text('job',$visit[0]->patient->job,array('id'=>'job','class'=>'form-control','placeholder'=>'المهنة')) !!}
									@endif
									@if ($errors->has('job'))<span class="help-block">{{$errors->first('job')}}</span>@endif
								</div>
							
							@endif
						</div>

					</div>
				  </div>
				  <!-- /.box-body -->

				  <div class="box-footer">
					<button type="button" class="btn btn-primary"  onclick="$('#patient_form').submit();" >تعديل</button>
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
$(document).ready(function(){
  $('#sid').on('change paste',function(){
      $("#err_msg").hide();
      if($('#sid').val().length == 14){
			     calculateBOD($('#sid').val());
		  }
	});
	/* Change ticket status */
	$('#ticket_status_select').on('change',function(){
			$('#ticket_number').val('');
			if($(this).val() == 'F')
				$('#ticket_number').attr('readonly','readonly');
			else
				$('#ticket_number').removeAttr('readonly');
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
	}
	if( isNaN(diffYears) || diffYears < 0 || isNaN(diffMonths) ){
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
</script>
@endsection
