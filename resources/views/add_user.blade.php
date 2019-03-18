@extends('layouts.app')
@section('title')
أضافة مستخدم
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>
        بيانات المستخدم
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">ادارة المستخدمين</li>
      </ol>
	  
    </section>
	<div id="content"></div>
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
				{!! Form::open(array('class'=>'form','name'=>'user_form')) !!}
				  <div class="box-body">
				   	
					@if(Session::has('flash_message'))
						@if(Session::has('message_type'))
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
							@if ($errors->has('name'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('أسم المستخدم',null,array('style'=>'color:red')) !!}
							  @if(isset($user_data))
								{!! Form::text('name',$user_data['id'],array('disabled','class'=>'form-control','id'=>'name','placeholder'=>'أسم المستخدم')) !!}
							  @else
								{!! Form::text('name',null,array('class'=>'form-control','id'=>'name','placeholder'=>'أسم المستخدم')) !!}
							  @endif
							  @if ($errors->has('name'))<span class="help-block">{{ $errors->first('name') }}</span>@endif
							</div>
							@if($errors->has('email'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('البريد الألكتروني',null,array('style'=>'color:red')) !!}
							  @if(isset($user_data))
								{!! Form::email('email',$user_data['email'],array('class'=>'form-control','id'=>'email','placeholder'=>'البريد الألكتروني'
								)) !!}
							  @else
								{!! Form::email('email',null,array('class'=>'form-control','id'=>'email','placeholder'=>'البريد الألكتروني')) !!}
							  @endif
							  @if ($errors->has('email'))<span class="help-block">{{$errors->first('email')}}</span>@endif
							</div>
							
							
							
						</div> <!-- col-lg-12 -->
						<div class="col-lg-6" style="float:right">
							@if ($errors->has('password'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('كلمة المرور',null,array('id'=>'pass_label','style'=>'color:red')) !!}
							  <input type="password" class="form-control" name="password" id="password"  />
							  @if ($errors->has('password'))<span class="help-block">{{$errors->first('password')}}</span>@endif
							</div>
							@if ($errors->has('cpassword'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('تأكيد كلمة المرور',null,array('id'=>'cpass_label','style'=>'color:red')) !!}
							  <input type="password" class="form-control" name="cpassword" id="cpassword"  />
							  @if ($errors->has('cpassword'))<span class="help-block">{{$errors->first('cpassword')}}</span>@endif
							</div>
							@if ($errors->has('role'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('الدور',null,array('style'=>'color:red')) !!}
							  @if(isset($user_data))
								{!! Form::select('role',$roles,null,['id'=>'role','class' => 'form-control']); !!}
							  @else	
								{!! Form::select('role',$roles,null,['id'=>'role','class' => 'form-control']); !!}
							  @endif	
							  @if ($errors->has('role'))<span class="help-block">{{$errors->first('role')}}</span>@endif
							  {!! Form::hidden('uid',null,array('id'=>'uid')) !!}
							</div>
						</div>
					</div><!-- row -->
				  </div>
				  <!-- /.box-body -->
				
				  <div class="box-footer">
					<button type="submit" id="submit" class="btn btn-primary">تسجيل</button>
					<input type="reset" class="btn btn-success" value="جديد" onclick="$('#err_msg').hide();$('.form input').removeAttr('disabled');
					$('#submit').text('تسجيل');$('#uid').val('');$('#pass_label').css('color','red');
	$('#cpass_label').css('color','red');" />
				  </div>
				{!! Form::close() !!}
			  </div>
			 <div class="box" dir="rtl">
			<div class="box-body">
					<div class="row">
						<div class="col-lg-12">
							<table id="example2" class="table table-bordered table-hover">
								<thead>
								<tr>
								  <th style="text-align:center">أسم المستخدم</th>
								  <th style="text-align:center">البريد الألكتروني</th>
								  <th style="text-align:center">الدور</th>
								  <th style="text-align:center">تعديل</th>
								  <th style="text-align:center">حذف</th>
								</tr>
								</thead>
								<tbody>
								<?php $i=0; ?>
								@foreach($users as $row)
								<tr id="row{{$i}}">
								  <td>{{$row->username}}</td>
								  <td>{{$row->email}}</td>
								  <td>{{$row->rolename}}</td>
								  <td style="text-align:center">
									<a href="#content" onclick="loadData({{$i}},{{$row->id}})" class="btn btn-info"><i class="fa fa-edit"></i> تعديل</a></td>
								  </td>
								  <td style="text-align:center">
									{!! Form::open(['method' => 'DELETE','action' => ['AdminController@destroyUser', $row->id],'onclick'=>'return display_delete_message();']) !!}
										{!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i> حذف', ['type'=>'submit','class' => 'btn btn-danger']) !!}
									{!! Form::close() !!}
								  </td>
								</tr>
								<?php $i++;?>
								@endforeach
								</tbody>
							</table>
						</div> <!-- ./col-lg-12 -->
					</div>  <!-- /.row -->
				</div> <!-- ./box body -->
			</div> <!-- ./box -->
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

	if($("#uid").val() != ""){
		$("#pass_label").css('color','black');
		$("#cpass_label").css('color','black');
		$("#submit").text('تعديل');
	}
});
function display_delete_message(){
	if(confirm('هل تريد حذف هذا المستخدم؟')){
		return true;
	}
	else{
		$("#overlay").hide();
		return false;
	}
}
// Function load user data into input text
function loadData(rowid,mid){
	$("#overlay").show();
	$("#name").val($('#row'+rowid+' td:eq(0)').text());
	$("#email").val($('#row'+rowid+' td:eq(1)').text());
	$("#pass_label").css('color','black');
	$("#cpass_label").css('color','black');
	switch( $('#row'+rowid+' td:eq(2)').text() )
	{
		case "الطبيب":
			$("#role option:eq(0)").prop('selected',true);
			break;
		case "موظف مكتب دخول":
			$("#role option:eq(1)").prop('selected',true);
			break;
		case "موظف مكتب حجز تذاكر العيادات":
			$("#role option:eq(2)").prop('selected',true);
			break;
		case "مسئول عن النظام":
			$("#role option:eq(3)").prop('selected',true);
			break;
		case "موظف مكتب استقبال":
			$("#role option:eq(4)").prop('selected',true);
			break;
	}
	
	$("#uid").val(mid);
	$("#submit").text('تعديل');
	$("#overlay").hide();
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

function removeDisabled(){
	$('#age').removeAttr('disabled');
	$('#pid').removeAttr('disabled');
}
</script>
@endsection
