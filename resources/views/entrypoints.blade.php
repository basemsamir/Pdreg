@extends('layouts.app')
@section('title')
إدارة مكاتب مستخدمي النظام
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>
        بيانات مكتب مستخدمي النظام
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">أضافة المكاتب</li>
        <li class="active">إدارة مكاتب مستخدمي النظام</li>
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
						<div class="col-lg-12" style="float:right">
							
							<div class="form-group @if($errors->has('name')) {{ 'has-error'}} @endif">
							  {!! Form::label('أسم المكتب',null,array('style'=>'color:red')) !!}
							  {!! Form::text('name',null,array('class'=>'form-control','id'=>'name','placeholder'=>'أسم المكتب')) !!}
							 
							  @if ($errors->has('name'))<span class="help-block">{{ $errors->first('name') }}</span>@endif
							</div>
							
							<div class="form-group @if($errors->has('type')) {{ 'has-error'}} @endif">
							  {!! Form::label('نوع المكتب',null,array('style'=>'color:red')) !!}
							  {!! Form::select('type',$data_entry_place_types,'1',['id'=>'type','class' => 'form-control']); !!}
							  
							  @if ($errors->has('type'))<span class="help-block">{{$errors->first('type')}}</span>@endif
							</div>
							
							<div id="sub_type_radios">
								<label class="">
								  
								  <div class="iradio_minimal-blue checked" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="radio" name="r1" value="entry_only" class="minimal" id="r1" checked="" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins><label id="r1_label">دخول مريض فقط</label></div>
								</label>
								<label class="">
								  <div class="iradio_minimal-blue " aria-checked="true" aria-disabled="false" style="position: relative;"><input type="radio" name="r1" value="exit_only" class="minimal" id="r2" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins><label id="r2_label">خروج مريض فقط</label></div>
								</label>
								<label class="">
								  <div class="iradio_minimal-blue " aria-checked="true" aria-disabled="false" style="position: relative;"><input type="radio" name="r1" value="entry_and_exit" id="r3" class="minimal" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins><label id="r3_label">دخول و خروج مريض معا</label></div>
								</label>
							</div>
							
							<div class="form-group @if($errors->has('location')) {{ 'has-error'}} @endif">
							  {!! Form::label('مكان المكتب',null,array('style'=>'color:red')) !!}
							  {!! Form::text('location',null,array('class'=>'form-control','id'=>'location','placeholder'=>'مكان المكتب')) !!}
							  
							  @if ($errors->has('location'))<span class="help-block">{{$errors->first('location')}}</span>@endif
							  {!! Form::hidden('eid',null,array('id'=>'eid')) !!}
							</div>
							
						</div> <!-- col-lg-12 -->
					</div><!-- row -->
				  </div>
				  <!-- /.box-body -->
				
				  <div class="box-footer">
					{!! Form::submit('تسجيل',array('class'=>'btn btn-primary','id'=>'submit')) !!}
					{!! Form::reset("جديد",array('class'=>'btn btn-success','onclick'=>"$('#sub_type_radios').hide();$('#err_msg').hide();$('#eid').val('');$('#submit').val('تسجيل');")) !!}
				  </div>
				{!! Form::close() !!}
				</div>
				 
				<!-- ./box -->
			</div>
			<!-- ./col -->
		  </div> <!-- ./box -->
		    <div class="box" dir="rtl">
				<div class="box-body">
					<div class="row">
						<div class="col-lg-12">
								<table id="example2" class="table table-bordered table-hover">
									<thead>
									<tr>
									  <th style="text-align:center">أسم المكتب</th>
									  <th style="text-align:center">نوع المكتب</th>
									  <th style="text-align:center"></th>
									  <th style="text-align:center">مكان المكتب</th>
									  <th style="text-align:center">تعديل</th>
									  
									</tr>
									</thead>
									<tbody>
									<?php $i=0; ?>
										@foreach($entrypoints as $row)
										<tr id="row{{$i}}">
										  <td>{{$row->name}}</td>
										  <td>{{$row->data_entry_place_type->name}}</td>
										  <td>@if($row->sub_type != null)
												{{$row->sub_type=="entry_only"?"دخول مريض فقط":($row->sub_type=="exit_only"?"خروج مريض فقط":"دخول و خروج مريض معا")}}
											  @endif</td>
										  <td>{{$row->location}}</td>
										  <td style="text-align:center">
											<a nohref onclick="loadData({{$i}},{{$row->id}},{{$row->type}},'{{$row->sub_type}}')" class="btn btn-info"><i class="fa fa-edit"></i> تعديل</a></td>
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
$(document).ajaxStart(function(){
    $("#overlay").show();
});
$(document).ajaxComplete(function(){
    $("#overlay").hide();
});
$(document).ready(function(){

	$("#sub_type_radios").hide();
	if($("#type").val() == 2){
		$("#sub_type_radios").show();
	}
	if($("#eid").val() != ""){
		$("#submit").text('تعديل');
	}
	
	$("#type").change(function(){
		if($("#type").val() == 2)
			$("#sub_type_radios").show();
		else
			$("#sub_type_radios").hide();
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
// Function load entrypoint data into input text
function loadData(rowid,eid,type_id,sub_type){
	$("#overlay").show();
	$("#name").val($('#row'+rowid+' td:eq(0)').text());
	$("#location").val($('#row'+rowid+' td:eq(3)').text());
	$("#type").val(type_id);
	if(type_id == 2){
		if(sub_type=="entry_only")
			$('#r1').iCheck('check');
		else if(sub_type=="exit_only")
			$('#r2').iCheck('check');
		else
			$('#r3').iCheck('check');
	}
	else{
		$("#sub_type_radios").hide();
	}
	if(type_id == 2)
		$("#sub_type_radios").show();
	else
		$("#sub_type_radios").hide();
	
	$("#eid").val(eid);
	$("#submit").val('تعديل');
	$("#overlay").hide();
}

</script>
@endsection
