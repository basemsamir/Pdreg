@extends('layouts.app')
@section('title')
أضافة أطباء الأقسام
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">الوحدات الطبية</li>
        <li class="active">أضافة أطباء الأقسام</li>
      </ol>
	  <h1>
        أضافة أطباء الأقسام
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
				{!! Form::open(array('class'=>'form','name'=>'user_form')) !!}
				  <div class="box-body">
				   	
					@if(Session::has('flash_message'))
						@if(Session::get('message_type') == "false")
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
							@if ($errors->has('doc_name'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('أسم الطبيب',null,array('style'=>'color:red')) !!}
							  {!! Form::select('doc_name',$doctors,null,['class' => 'form-control']); !!}
							 
							  @if ($errors->has('doc_name'))<span class="help-block">{{ $errors->first('doc_name') }}</span>@endif
							</div>
							
							
							@if ($errors->has('department'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('أسم القسم',null,array('style'=>'color:red')) !!}
							  {!! Form::select('department',$departments,null,['class' => 'form-control']); !!}
							 
							  @if ($errors->has('department'))<span class="help-block">{{ $errors->first('department') }}</span>@endif
							</div>
							
						</div> <!-- col-lg-12 -->
					</div><!-- row -->
				  </div>
				  <!-- /.box-body -->
				
				  <div class="box-footer">
					<button type="submit" class="btn btn-primary" id="submit">أضافة</button>
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
								  <th style="text-align:center">القسم</th>
								  <th style="text-align:center">الطبيب</th>
								  <th style="text-align:center">البريد الألكتروني</th>
								  <th style="text-align:center">ألغاء الأضافة</th>
								</tr>
								</thead>
								<tbody>
									<?php $i=0; ?>
									@foreach($doctor_departments as $row)
									<tr id="row{{$i}}">
									  <td>{{$row->medicalname}}</td>
									  <td>{{$row->username}}</td>
									  <td>{{$row->email}}</td>
									  <td align="center"><a href='{{ url("admin/delete_dep_user/$row->user_id&$row->dep_id") }}'
										  class="btn btn-danger" 
										  onclick="if(confirm('هل تريد ألغاء هذة الأضافة ؟ ')){return true;}else{return false;}" ><i class="fa fa-close"></i></a></td>
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

