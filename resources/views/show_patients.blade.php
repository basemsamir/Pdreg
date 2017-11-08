@extends('layouts.app')
@section('title')
دليل المرضى
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">دليل المرضي</li>
      </ol>
	  <h1>
        دليل المرضى
        <small>دليل بيانات المرضى</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-12 col-xs-24">
          <!-- small box -->
			  <div class="box box-primary" dir="rtl">
				<div class="box-header with-border">
				  <h3 class="box-title">جدول البحث</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">

					<div class="row" >
					{!! Form::open(array('class'=>'form-inline','name'=>'patient_form')) !!}
					 <div class="col-lg-4">
						<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> بحث</button>
					 </div>
					 <div class="col-lg-4">
					    <div class="form-group">
						  {!! Form::label('أسم المريض') !!}
						  {!! Form::text('name',null,array('class'=>'form-control','placeholder'=>'أسم المريض')) !!}
						</div>
						<br><br>
						<div class="form-group">
						  {!! Form::label('تاريخ الميلاد') !!}
						  {!! Form::text('birthdate',null,array('id'=>'datepicker','class'=>'form-control','placeholder'=>'تاريخ الميلاد')) !!}
						</div>
					 </div>
					 <div class="col-lg-4">
					    <div class="form-group">
						  {!! Form::label('كود المريض') !!}
						  {!! Form::text('code',null,array('class'=>'form-control','placeholder'=>'كود المريض')) !!}
						</div><br><br>
						<div class="form-group">
						  {!! Form::label('الرقم القومي') !!}
						  {!! Form::text('sin',null,array('class'=>'form-control','placeholder'=>'الرقم القومي')) !!}
						</div>
					 </div>
					{!! Form::close() !!}
					</div>
					<br>

					<div class="row">
						<div class="col-lg-12">
							<h3>{{$table_header}}</h3>
							<table id="example1" class="table table-bordered table-hover">
								<thead>
								<tr>
								  <th>الكود</th>
								  <th>الأسم</th>
								  <th>النوع</th>
								  <th>العنوان</th>
								  <th>الرقم القومي</th>
								  <th>تاريخ الميلاد</th>
								  <th>تاريخ و وقت التسجيل</th>
								  @if($role_name == 'Admin' || $role_name == 'SubAdmin')
									<th style="text-align:center">تعديل بيانات المريض</th>
								  @elseif($role_name == 'Entrypoint' || $role_name == 'Receiption')
									<th style="text-align:center">طباعة كارت المريض</th>
								  @endif
								  @if($role_name == 'Entrypoint' )
									<th style="text-align:center">تسجيل دخول جديد</th>
								  @elseif($role_name == 'Receiption' || $role_name == 'Desk')
									<th style="text-align:center">تسجيل كشف جديد</th>
								  @elseif($role_name == 'Doctor')
									<th style="text-align:center">سجل المريض</th>
								  @endif
								</tr>
								</thead>
								<tbody>
								@foreach($data as $row)
								<tr>
								  <td>{{$row->id}}</td>
								  <td>{{$row->name}}</td>
								  <td>{{$row->gender=='M'?'ذكر':'أنثي'}}</td>
								  <td>{{$row->address}}</td>
								  <td>{{$row->sid}}</td>
								  <td>{{$row->birthdate}}</td>
								  <td>{{$row->created_at}}</td>
								  @if($role_name == 'Admin' || $role_name == 'SubAdmin')
									<td style="text-align:center"><a href=' {{ url("admin/$row->id&-1/edit") }} '  class="btn btn-success"><i class="fa fa-edit"></i> تعديل</a></td>
								  @elseif($role_name == 'Entrypoint' || $role_name == 'Receiption')
									<td style="text-align:center"><a href='{{ url("/printpatientcard/$row->id") }}' target="_blank" class="btn btn-info"><i class="fa fa-print"></i> طباعة</a></td>
								  @endif
								  @if($role_name == 'Entrypoint')
									<td style="text-align:center"><a href="visits/{{$row->id}}&-1" class="btn btn-success"><i class="fa fa-plus"></i> تسجيل</a></td>
								    @elseif($role_name == 'Receiption')
									<td style="text-align:center"><a href='{{ url("/patients/reserve/$row->id") }}' class="btn btn-success"><i class="fa fa-plus"></i> تسجيل</a></td>
									@elseif($role_name == 'Desk')
									<td style="text-align:center"><a href='{{ url("/patients/desk/$row->id") }}' class="btn btn-success"><i class="fa fa-plus"></i> تسجيل</a></td>
									@elseif($role_name == 'Doctor')
									<td style="text-align:center"><a href='{{ url("/visits/printhistory/$row->id") }}'  target="_blank" class="btn btn-info"><i class="fa fa-print"></i></a></td>
								  @endif
								</tr>
								@endforeach
								</tbody>
							</table>
						</div> <!-- ./col-lg-12 -->
					</div> <!-- ./row -->
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
