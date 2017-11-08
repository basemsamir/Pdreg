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
				{!! Form::open(array('class'=>'form','method'  => 'patch','action' => ['AdminController@updatePatient',$patient_data['id'],$vid],'id'=>'patient_form')) !!}
				  <div class="box-body">

					@if(Session::has('success'))
						<div class="alert alert-success">
							<b>{{ Session::get('success') }} </b>
						</div>
					@endif
					<div class="alert alert-danger" style="display: none" id="err_msg"></div>
					<div class="row">
						<div class="col-lg-6" style="float:right">

              @if(!is_null($visit))
                <div class="form-group @if($errors->has('ticket_number')) has-error @endif ">
                  {!! Form::label('رقم التذكرة',null) !!}
                  {!! Form::text('ticket_number',$visit->ticket_number,array('class'=>'form-control','onkeypress'=>'return isNumber(event)')) !!}
                  @if ($errors->has('ticket_number'))<span class="help-block">{{$errors->first('ticket_number')}}</span>@endif
                  {!! Form::hidden('ticket_type',$visit->ticket_type) !!}

                </div>

              @endif

							@if($errors->has('sid'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('رقم البطاقة',null) !!}
							  {!! Form::text('sid',$patient_data['sid'],array('size'=>'14','class'=>'form-control','id'=>'sid','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("sid")')) !!}
							  @if ($errors->has('sid'))<span class="help-block">{{$errors->first('sid')}}</span>@endif
							</div>
							@if ($errors->has('fname') || $errors->has('sname') || $errors->has('mname') || $errors->has('lname'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('الأسم',null,array('style'=>'color:red')) !!} <br>
							  <?php $name_arr=explode(" ",$patient_data['name']);?>
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
							@if ($errors->has('gender'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('النوع',null,array('style'=>'color:red')) !!}
							  {!! Form::select('gender',[''=>'أختر النوع','M' => 'ذكر', 'F' => 'أنثى'], $patient_data['gender'],['class'=>'form-control','id'=>'gender_select']); !!}
							  @if ($errors->has('gender'))<span class="help-block">{{$errors->first('gender')}}</span>@endif
							</div>
						</div>
						<div class="col-lg-6" style="float:right">
							@if($errors->has('year_age'))
								<div class="form-group has-error">
							@else

								<div class="form-group">
							@endif
                {!! Form::label('العمر (عدد الأيام / عدد الأشهر / عدد السنين )',null,array('style'=>'color:red')) !!} <br>
                <?php
									  $current_date = new DateTime();
									  $birthdate = new DateTime($patient_data['birthdate']);
									  $interval = $current_date->diff($birthdate);
								?>
                {!! Form::select('day_age',$days,$interval->d,['class'=>'form-control','id'=>'day_age','style'=>'width:29%;display:inline']) !!}
								{!! Form::select('month_age',$ages,$interval->m,['class'=>'form-control','id'=>'month_age','style'=>'width:39%;display:inline']); !!}
								{!! Form::text('year_age',$interval->y,array('class'=>'form-control','id'=>'year_age','onkeypress'=>'return isNumber(event)','style'=>'width:29%;display:inline')) !!}
							  @if($errors->has('year_age'))<span class="help-block">{{$errors->first('year_age')}}</span>@endif
							</div>
							@if ($errors->has('address'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('العنوان',null,array('style'=>'color:red')) !!}
							  {!! Form::text('address',$patient_data['address'],array('class'=>'form-control','id'=>'address')) !!}
							  @if ($errors->has('address'))<span class="help-block">{{$errors->first('address')}}</span>@endif
							</div>

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
