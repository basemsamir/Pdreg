@extends('layouts.app')
@section('title')
حجز تذكرة استقبال
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>
        بيانات المريض
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">حجز تذكرة استقبال</li>
      </ol>
	  
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
								<b>{!! Session::get('flash_message') !!} </b>
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
							<div class="form-group @if($errors->has('serial_number')) has-error @endif">
								{!! Form::label('رقم التسلسل',null,array('style'=>'color:red')) !!}
								{!! Form::text('serial_number',null,array('class'=>'form-control','placeholder'=>'رقم التسلسل','onkeypress'=>'return isNumber(event)')) !!}
								@if ($errors->has('serial_number'))<span class="help-block">{{$errors->first('serial_number')}}</span>@endif
							
							</div>
							<div class="form-group @if($errors->has('reg_date')) has-error @endif">
								{!! Form::label('تاريخ التسجيل',null,array('style'=>'color:red')) !!}
								{!! Form::text('reg_date',null,array('class'=>'form-control','id'=>'datepicker','placeholder'=>'1900-01-01')) !!}
								@if ($errors->has('reg_date'))<span class="help-block">{{$errors->first('reg_date')}}</span>@endif
							</div>
							<div class="bootstrap-timepicker">
							  <div class="form-group @if($errors->has('reg_time')) has-error @endif">
								  {!! Form::label('reg_time','ساعة التسجيل',array('style'=>'color:red')) !!}
								  {!! Form::text('reg_time',null,array('class'=>'form-control timepicker')) !!}
									@if ($errors->has('reg_time'))<span class="help-block">{{$errors->first('reg_time')}}</span>@endif
							
							  </div>
						  </div>
							<div class="form-group">
							  {!! Form::label('حالة التذكرة',null,array('style'=>'color:red')) !!}
								{!! Form::select('ticket_status',['T'=>'عادي','F' => 'مجاني'], 'T',['class'=>'form-control','id'=>'ticket_status_select']); !!}
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
							<div class="form-group">
								{!! Form::label('المشاهدة',null) !!}
								{!! Form::text('watching_status',null,array('class'=>'form-control','placeholder'=>'المشاهدة')) !!}
							</div>
							<div class="form-group @if($errors->has('ticket_type'))  has-error @endif">
							    {!! Form::label('نوع التذكرة',null,array('style'=>'color:red')) !!}
								{!! Form::select('ticket_type',['G' => 'استقبال عام', 'T' => 'استقبال اصابات'], 'G',['class'=>'form-control','id'=>'ticket_type_select']) !!}
							    @if ($errors->has('ticket_type'))<span class="help-block">{{$errors->first('ticket_type')}}</span>@endif
							</div>
							
              <div class="form-group @if ($errors->has('medical_id')) has-error @endif">
							   {!! Form::label('أسم القسم',null,array('style'=>'color:red')) !!}
							   {!! Form::select('medical_id',$medical_units,null,['id'=>'medical_id','class' => 'form-control']); !!}
             		 @if ($errors->has('medical_id'))<span class="help-block">{{$errors->first('medical_id')}}</span>@endif
							</div>
							<div class="form-group">
								<div class="checkbox icheck">
									<label>
										<input type="checkbox" id="all_deps" name="all_deps" />
										إستشكاف طاريء
									</label>
								</div>
							</div>
						</div>
						<div class="col-lg-4" style="float:right">
							@if($errors->has('sid'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('رقم البطاقة',null) !!}
							  @if(isset($patient_data['sid']) && $patient_data['sid'] !="" )
								{!! Form::text('sid',$patient_data['sid'],array('size'=>'14','class'=>'form-control','id'=>'sid','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen(this)','disabled')) !!}
                {!! Form::hidden('hidden_sid',$patient_data['sid']) !!}
                @else
								{!! Form::text('sid',null,array('size'=>'14','class'=>'form-control','id'=>'sid','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen(this)')) !!}
                {!! Form::hidden('hidden_sid',null,array('id'=>'hidden_sid')) !!}
                @endif
							  @if ($errors->has('sid'))<span class="help-block">{{$errors->first('sid')}}</span>@endif
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
							<div class="form-group @if ($errors->has('job')) has-error @endif">
							  {!! Form::label('المهنة',null,array('style'=>'color:red')) !!}
								@if(isset($patient_data) && $patient_data['job'] != "")
							 	 {!! Form::text('job',$patient_data['job'],array('id'=>'job','disabled','class'=>'form-control','placeholder'=>'المهنة')) !!}
								@else
									{!! Form::text('job',null,array('id'=>'job','class'=>'form-control','placeholder'=>'المهنة')) !!}
								@endif
								@if ($errors->has('job'))<span class="help-block">{{$errors->first('job')}}</span>@endif
							
							</div>
							@if ($errors->has('address'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('العنوان',null,array('style'=>'color:red')) !!}
							  @if(isset($patient_data) && $patient_data['address'] != "")
								{!! Form::text('address',$patient_data['address'],array('disabled','class'=>'form-control','id'=>'address')) !!}
							  @else
							  {!! Form::text('address',null,array('id'=>'address','class'=>'form-control','placeholder'=>'العنوان')) !!}
							  @endif
							  @if ($errors->has('address'))<span class="help-block">{{$errors->first('address')}}</span>@endif
							</div>
							<div class="form-group @if ($errors->has('sent_by_person')) has-error @endif">
							  {!! Form::label('مرسل بمعرفة',null,array('style'=>'color:red')) !!}
							  {!! Form::textarea('sent_by_person',null,array('class'=>'form-control','rows'=>5,'cols'=>4)) !!}
								@if ($errors->has('sent_by_person'))<span class="help-block">{{$errors->first('sent_by_person')}}</span>@endif
							
							</div>
							<div class="form-group @if ($errors->has('ticket_companion_name')) has-error @endif">
							  {!! Form::label('أسم مرافق الحجز',null) !!}
							  {!! Form::text('ticket_companion_name',null,array('class'=>'form-control','placeholder'=>'أسم مرافق الحجز')) !!}
								@if ($errors->has('ticket_companion_name'))<span class="help-block">{{$errors->first('ticket_companion_name')}}</span>@endif
							
							</div>
							<div class="form-group  @if ($errors->has('ticket_companion_sin')) has-error @endif">
							  {!! Form::label('رقم البطاقة مرافق الحجز',null) !!}
							  {!! Form::text('ticket_companion_sin',null,array('class'=>'form-control','placeholder'=>'رقم البطاقة مرافق الحجز','onkeypress'=>'return isNumber(event)&&isForteen(this)')) !!}
								@if ($errors->has('ticket_companion_sin'))<span class="help-block">{{$errors->first('ticket_companion_sin')}}</span>@endif
							</div>
              @if ($errors->has('entry'))
                <div class="form-group has-error">
              @else
  							<div class="form-group"  >
              @endif
  							   {!! Form::label('مكتب الاستقبال',null,array('style'=>'color:red')) !!}
  							   {!! Form::select('entry',$entrypoints,null,['class' => 'form-control']); !!}
              @if ($errors->has('entry'))<span class="help-block">{{$errors->first('entry')}}</span>@endif

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
                <legend>بيانات الملف</legend>
									<div class="form-group @if($errors->has('entry_date')) has-error @endif">
										{!! Form::label('تاريخ الدخول',null) !!}
										{!! Form::text('entry_date',null,array('class'=>'form-control','id'=>'datepicker2','placeholder'=>'1900-01-01')) !!}
										@if ($errors->has('entry_date'))<span class="help-block">{{$errors->first('entry_date')}}</span>@endif
									</div>
									<div class="bootstrap-timepicker">
										<div class="form-group @if($errors->has('entry_time')) has-error @endif">
											{!! Form::label('entry_time','ساعة الدخول') !!}
											{!! Form::text('entry_time',null,array('class'=>'form-control timepicker')) !!}
											@if ($errors->has('entry_time'))<span class="help-block">{{$errors->first('entry_time')}}</span>@endif
									
										</div>
									</div>
  								<div class="form-group">
										{!! Form::label('room_number','رقم الغرفة') !!}
										{!! Form::text('room_number',null,array('class'=>'form-control','placeholder'=>'رقم الغرفة')) !!}
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
									<div class="form-group">
						      	{!! Form::label('نوع العلاج') !!}
						      	{!! Form::select('cure_type_id',$cure_types,null,['class' => 'form-control']); !!}
						  		</div>
									<div class="form-group">
										{!! Form::label('contract','جهة التعاقد') !!}
										{!! Form::text('contract',null,array('class'=>'form-control','placeholder'=>'جهة التعاقد')) !!}
									</div>
									<div class="form-group">
										{!! Form::label('محول بواسطة',null) !!}
										{!! Form::text('converted_by_doctor',null,array('class'=>'form-control','placeholder'=>'محول بواسطة')) !!}
									</div>
									<div class="form-group" >
										{!! Form::label('الطبيب المعالج') !!}
										{!! Form::select('reference_doctor_id',$first_clinic_doctors,null,['id'=>'reference_doctor_id','class' => 'form-control']); !!}
								   </div>
              </fieldset>
              <fieldset>
                <legend>بيانات المرافق</legend>
                	<div class="form-group @if($errors->has('c_name')) has-error @endif">
										{!! Form::label('أسم المرافق',null) !!}
										{!! Form::text('c_name',null,array('class'=>'form-control','placeholder'=>'أسم المرافق')) !!}
										@if ($errors->has('c_name'))<span class="help-block">{{$errors->first('c_name')}}</span>@endif
									</div>
									<div class="form-group @if($errors->has('c_sid')) has-error @endif">
										{!! Form::label('رقم بطاقة المرافق',null) !!}
										{!! Form::text('c_sid',null,array('class'=>'form-control','placeholder'=>'رقم بطاقة المرافق','onkeypress'=>'return isNumber(event)&&isForteen(this)')) !!}
										@if ($errors->has('c_sid'))<span class="help-block">{{$errors->first('c_sid')}}</span>@endif
									</div>
                @if ($errors->has('relation_id'))
  								<div class="form-group has-error">
  							@else
  								<div class="form-group">
  							@endif
  							  {!! Form::label('درجة القرابة',null) !!}
  							  {!! Form::select('relation_id',$relations,null,['class' => 'form-control']); !!}
  							  @if ($errors->has('relation_id'))<span class="help-block">{{$errors->first('relation_id')}}</span>@endif
  							  </div>
                @if ($errors->has('c_address'))
  								<div class="form-group has-error">
  							@else
  								<div class="form-group">
  							@endif
  							  {!! Form::label('العنوان',null) !!}
  							  {!! Form::text('c_address',null,array('id'=>'address','class'=>'form-control','placeholder'=>'العنوان')) !!}
  							  @if ($errors->has('c_address'))<span class="help-block">{{$errors->first('c_address')}}</span>@endif
  							</div>
                @if ($errors->has('c_job'))
                  <div class="form-group has-error">
                @else
                  <div class="form-group">
                @endif
                  {!! Form::label('المهنة') !!}
                  {!! Form::text('c_job',null,array('id'=>'address','class'=>'form-control','placeholder'=>'العنوان')) !!}
                  @if ($errors->has('c_job'))<span class="help-block">{{$errors->first('c_job')}}</span>@endif
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
	 
	 if($('#sid').val() != ""){
		 calculateBOD($('#sid').val(),$('#year_age'));
	 }

	 if($('#ticket_status_select').val()=='F'){
			$('#ticket_num').attr('readonly','readonly');
		
	 }
	 else{
		$('#ticket_num').removeAttr('readonly');
		$('#ticket_num').focus();
	 }

   if($("#reservation_type").val() == "T&E")
     $("#companionDiv").show();
   else {
     $("#companionDiv").hide();
	 
	  $("#reference_doctor_id").prepend('<option></option>');
		$("#reference_doctor_id").prop('selectedIndex',0);
  }
	/* Change ticket status */
	$('#ticket_status_select').on('change',function(){
			$('#ticket_num').val('');
			if($(this).val() == 'F')
				$('#ticket_num').attr('readonly','readonly');
			else
				$('#ticket_num').removeAttr('readonly');
	});
	/* Disable department field in case of emergency */
	$("#all_deps").on('ifChanged',function(){
		if($("#medical_id").attr("disabled"))
			$("#medical_id").removeAttr("disabled");
		else
			$("#medical_id").attr("disabled","disabled");
	});
	/* Change department to get its attached doctors in reference_doctor_id select box */
	$("#medical_id").change(function(){
		if($("#reservation_type").val() == "T&E"){
				var url = "{{ url('/patients/getDepartmentDoctors/') }}";
				$.ajax({
					type: "POST",
					url: url,
					data: { 'mid':$("#medical_id").val() },
					success: function (data) {
						$("#reference_doctor_id").empty();
						if(data['success']=='true'){
							$("#reference_doctor_id").append("<option></option>");
							for (i=0;i<data['data'].length;i++) {
								$("#reference_doctor_id").append("<option value='"+data['data'][i].id+"'>"+data['data'][i].name+"</option>");
							}
						}
					},
					error: function (data) {
						alert("Error");
					}
				});
		}
	});
  $('#sid').on('change paste',function(){
		$("#err_msg").hide();

			if($("#sid").val().length < 14){
				$("#year_age").removeAttr('disabled');
				$("#year_age").val("");
				$("#month_age").removeAttr('disabled');
				$("#month_age").prop('selectedIndex',0);
				$("#day_age").removeAttr('disabled');
				$("#day_age").prop('selectedIndex',0);
			}
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
  						if(data['data'][0].sid != null)
								$("#address").val(data['data'][0].address).attr('disabled',true);
							if(data['data'][0].sid != null)
								$("#job").val(data['data'][0].job).attr('disabled',true);
  						$("#gender_select").attr("disabled", true);
  						$("#month_age").attr("disabled", true);
							$("#day_age").attr("disabled", true);
  					}
  					else{
							if(!$("#pid").prop('disabled'))
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
		$("#err_msg").hide();
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
						$("#pid").attr('disabled',true);
						if(data['data'][0].sid != null)
							$("#sid").val(data['data'][0].sid).attr('disabled',true);
						if(data['data'][0].address != null)
							$("#address").val(data['data'][0].address).attr('disabled',true);
						if(data['data'][0].job != null)
							$("#job").val(data['data'][0].job).attr('disabled',true);
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
function isForteen(el){
	if($(el).val().length >= 14)
		return false;
	return true;
}
// Function calculates the age field
function calculateBOD(sid){
	
	if($("#year_age").next().length > 0){
		$("#year_age").parent().removeClass('has-error');
		$("#year_age").next().remove();
	}
		
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
		$("#year_age").attr('disabled','disabled');
		$("#month_age").val(diffMonths);
		$("#month_age").attr('disabled','disabled');
    $("#day_age").val(diffDays);
		$("#day_age").attr('disabled','disabled');
	}
}
function removeDisabled(){
	$('#medical_id').removeAttr('disabled');
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
