@extends('layouts.app')
@section('title')
{{ $title }}
@endsection
@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">{{ $title }}</li>
      </ol>
	  	<h1>{{ $title }}</h1>
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
							@if($errors->any())
							<div class="alert alert-danger">
								@foreach($errors->all() as $error)
									<p><b>{{ $error }}</b></p>
								@endforeach
							</div>
							@endif
							<div class="row" >
								<div class="col-md-12">
									<div class="form-inline">
										{!! Form::open(array('class'=>'form')) !!}
											{!! Form::label('رقم التذكرة',null) !!}
											{!! Form::text('ticket_number',null,['class'=>'form-control','autocomplete'=>'off']) !!}
											<button type="submit" class="btn btn-primary" >بحث</button>
										{!! Form::close() !!}
									</div>
								</div>
							</div>
						</div> <!-- box-body -->
					</div> <!-- box-primary -->
				</div>	<!-- col-lg-12 -->
				<div class="col-lg-12 col-xs-24">
					<div class="box box-primary" dir="rtl">
						<div class="box-body">
							<div class="row">
								<div class="col-lg-12">
									@if(isset($header))
										<h4>{!! $header !!}</h4>
									@endif
									<table id="example1" class="table table-bordered table-hover">
										<thead>
										<tr>
										  <th>الأشعة المطلوبة</th>
											<th>رقم التذكرة</th>
											<th>كود المريض</th>
											<th>أسم المريض</th>
										</tr>
										</thead>
										<tbody>
											@if(isset($data))
												@foreach($data as $row)
													<tr>
														<td>{!! $row->proc_name !!}</td>
														<td>{{ $row->ticket_number }}</td>
														<td>{{ $row->patient_id }}</td>
														<td>{{ $row->name }}</td>
													</tr>
												@endforeach
											@endif
										</tbody>
									</table>
								</div> <!-- ./col-lg-12 -->
							</div> <!-- ./row -->
						</div>
            <!-- ./box-body -->
        </div><!-- ./body -->
      </div> <!-- col -->
      </div> <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

@endsection
@section('javascript')
	<script src="{{asset('dist/js/date_interval.js')}}"></script>
@stop
