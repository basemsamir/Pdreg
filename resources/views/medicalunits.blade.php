@extends('layouts.app')
@section('title')
أدارة الوحدات الطبية
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
			<h1>
        بيانات الوحدة الطبية
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">الوحدات الطبية</li>
        <li class="active">أدارة الوحدات الطبية</li>
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
							@if ($errors->has('name'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('أسم الوحدة',null,array('style'=>'color:red')) !!}
							  {!! Form::text('name',null,array('class'=>'form-control','id'=>'name','placeholder'=>'أسم الوحدة')) !!}
							 
							  @if ($errors->has('name'))<span class="help-block">{{ $errors->first('name') }}</span>@endif
							</div>
							
							
							@if ($errors->has('type'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('نوع الوحدة',null,array('style'=>'color:red')) !!}
							  {!! Form::select('type',['c'=>'عيادة','d'=>'قسم'],null,['id'=>'type','class' => 'form-control']); !!}
							  
							  @if ($errors->has('type'))<span class="help-block">{{$errors->first('type')}}</span>@endif
							  {!! Form::hidden('mid',null,array('id'=>'mid')) !!}
							</div>
							
						</div> <!-- col-lg-12 -->
					</div><!-- row -->
				  </div>
				  <!-- /.box-body -->
				
				  <div class="box-footer">
					<button type="submit" class="btn btn-primary" id="submit">تسجيل</button>
					<input type="reset" class="btn btn-success" value="جديد" onclick="$('#err_msg').hide();$('#mid').val('');$('#submit').text('تسجيل');" />
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
									  <th style="text-align:center">نوع الوحدة</th>
									  <th style="text-align:center">أسم الوحدة</th>
									  <th style="text-align:center">تعديل</th>
									  
									</tr>
									</thead>
									<tbody>
									<?php $i=0; ?>
									@foreach($data as $row)
									<tr id="row{{$i}}">
									  <td>{{$row->type=='c'?'عيادة':'قسم'}}</td>
									  <td>{{$row->name}}</td>
									  <td style="text-align:center">
										<a nohref onclick="loadData({{$i}},{{$row->id}})" class="btn btn-info"><i class="fa fa-edit"></i> تعديل</a></td>
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

	if($("#mid").val() != ""){
		$("#submit").text('تعديل');
	}
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
// Function load medicalunit data into input text
function loadData(rowid,mid){
	$("#overlay").show();
	$("#name").val($('#row'+rowid+' td:eq(1)').text());
	if(	$('#row'+rowid+' td:eq(0)').text() == "قسم" )
		$("#type option:eq(1)").prop('selected',true);
	else
		$("#type option:eq(0)").prop('selected',true);
	$("#mid").val(mid);
	$("#submit").text('تعديل');
	$("#overlay").hide();
}
function removeDisabled(){
	$('#age').removeAttr('disabled');
	$('#pid').removeAttr('disabled');
}
</script>
@endsection
