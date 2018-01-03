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
        <li class="active">خروج مريض</li>
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
				{!! Form::open(array('class'=>'form','name'=>'patient_form','id'=>'patient_form')) !!}
				  <div class="box-body">

					@if(Session::has('flash_message'))
						@if(Session::get('message_type')=='false')
							<div class="alert alert-danger">
						@else
							<div class="alert alert-success">
						@endif
								<b>{{ Session::get('flash_message') }} </b>
							</div>
					@endif
					<div class="alert alert-danger" style="display: none" id="err_msg"></div>
					<div class="row">
						<div class="col-lg-6" style="float:right">
							<div class="form-group">
							  {!! Form::label('كود المريض',null) !!}
							  @if(isset($data))
								{!! Form::text('id',$data[0]->id,array('disabled','class'=>'form-control','id'=>'pid','placeholder'=>'كود المريض','onkeypress'=>'return isNumber(event)')) !!}
							  @else
								{!! Form::text('id',null,array('class'=>'form-control','id'=>'pid','placeholder'=>'كود المريض','onkeypress'=>'return isNumber(event)')) !!}
							  @endif
							  @if ($errors->has('id'))<span class="help-block">{{$errors->first('id')}}</span>@endif
							</div>

							<div class="form-group">
							  {!! Form::label('رقم البطاقة',null) !!}
							  @if(isset($data))
								{!! Form::text('sid',$data[0]->sid,array('size'=>'14','class'=>'form-control','id'=>'sid','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("sid")','disabled')) !!}
							  @else
								{!! Form::text('sid',null,array('size'=>'14','class'=>'form-control','id'=>'sid','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("sid")')) !!}
								{!! Form::hidden('hidden_sid',null,array('id'=>'hidden_sid')) !!}
							  @endif
							  @if ($errors->has('sid'))<span class="help-block">{{$errors->first('sid')}}</span>@endif
							</div>

							<div class="form-group">
							  {!! Form::label('الأسم',null) !!} <br>
							  @if(isset($data))
								<?php $name_arr=explode(" ",$data[0]->name);?>
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
							</div>

							<div class="form-group">
							  {!! Form::label('القسم',null) !!}
							  @if(isset($data))
								{!! Form::text('address',$data['0']->mname,array('disabled','class'=>'form-control','id'=>'address')) !!}
							  @else
							  {!! Form::text('address',null,array('id'=>'address','class'=>'form-control','placeholder'=>'العنوان')) !!}
							  @endif
							  @if ($errors->has('address'))<span class="help-block">{{$errors->first('address')}}</span>@endif
							</div>

							<div class="form-group">
							  {!! Form::label('الطبيب المعالج',null) !!}
							  @if(isset($data))
								{!! Form::text('address',$data['0']->uname,array('disabled','class'=>'form-control','id'=>'address')) !!}
							  @else
							  {!! Form::text('address',null,array('id'=>'address','class'=>'form-control','placeholder'=>'العنوان')) !!}
							  @endif
							  @if ($errors->has('address'))<span class="help-block">{{$errors->first('address')}}</span>@endif
							</div>
						</div>
						<div class="col-lg-6" style="float:right">

							<div class="form-group">
							  {!! Form::label('تاريخ الدخول',null,array('style'=>'color:red')) !!}
							  @if(isset($data))
								{!! Form::text('entry_date',$data[0]->entry_date,array('class'=>'form-control','id'=>'diagnosis','placeholder'=>'تاريخ الدخول','readonly')) !!}
							  @else
							  {!! Form::text('entry_date',null,array('id'=>'address','class'=>'form-control','placeholder'=>'تاريخ الدخول')) !!}
							  @endif
							  @if ($errors->has('entry_date'))<span class="help-block">{{$errors->first('entry_date')}}</span>@endif
							</div>

							<div class="form-group @if ($errors->has('final_diagnosis')) has-error @endif">
							  {!! Form::label('التشخيص النهائي',null,array('style'=>'color:red')) !!}
							  @if(isset($data))
							  {!! Form::textarea('final_diagnosis',$data[0]->final_diagnosis,array('class'=>'form-control','id'=>'diagnosis','rows=4','coloumns=30','placeholder'=>'التشخيص النهائي')) !!}
							  @else
							  {!! Form::textarea('final_diagnosis',null,array('id'=>'address','class'=>'form-control','placeholder'=>'التشخيص النهائي')) !!}
							  @endif
							  @if ($errors->has('final_diagnosis'))<span class="help-block">{{$errors->first('final_diagnosis')}}</span>@endif
							</div>

							<div class="form-group @if ($errors->has('exit_condition')) has-error @endif">
								{!! Form::label('الحالة عند الخروج',null,array('style'=>'color:red')) !!}
                @if(isset($data))
								{!! Form::text('exit_condition',$data[0]->exit_status,array('id'=>'exit_condition','class'=>'form-control','placeholder'=>'الحالة عند الخروج')) !!}
                @else
                {!! Form::text('exit_condition',null,array('id'=>'exit_condition','class'=>'form-control','placeholder'=>'الحالة عند الخروج')) !!}
                @endif
                @if ($errors->has('exit_condition'))<span class="help-block">{{$errors->first('exit_condition')}}</span>@endif
							</div>

							<div class="form-group  @if ($errors->has('exit_time')) has-error @endif">
							   {!! Form::label('تاريخ الخروج',null,array('style'=>'color:red')) !!}
                 @if(isset($data))
                 {!! Form::text('exit_time',$data[0]->exit_date,array('id'=>'datepicker','class'=>'form-control','placeholder'=>'تاريخ الخروج')) !!}
							   @else
							   {!! Form::text('exit_time',null,array('id'=>'datepicker','class'=>'form-control','placeholder'=>'تاريخ الخروج')) !!}
                 @endif
                 @if ($errors->has('exit_time'))<span class="help-block">{{$errors->first('exit_time')}}</span>@endif
							</div>
						</div>


					</div>
				  </div>
				  <!-- /.box-body -->

				  <div class="box-footer">
					   <button type="submit" class="btn btn-primary" >تسجيل</button>
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
</script>
@stop
