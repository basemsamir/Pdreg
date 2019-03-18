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
			<div id="overlay"></div>
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
					{!! Form::open(array('class'=>'form-inline')) !!}
					 <div class="col-lg-4">
						<div class="form-group">
							<button type="submit" value="show_only" name="submit" class="btn btn-primary"><i class="fa fa-search"></i> عرض النتائج </button>
						</div>
						<div class="form-group">
							<a href="{{ url('admin/total_dr_patients_period_report') }}" target="_blank" class="btn btn-success @if(!Session::has('print_clinic_name')) disabled @endif "><i class="fa fa-print"></i> طباعة التقرير</a>
						
							<div class="form-group">
								<a href = "{{ url('admin/total_dr_patients_period') }}" class="btn btn-info"><i class="fa fa-search"></i> بحث جديد </a>
							</div>
						</div>
					 </div>
					 @include('reports.search_form')

					{!! Form::close() !!}
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
								<h4 style="direction: rlt">{!! $header !!}</h4>
							@endif
							<table id="example1" class="table table-bordered table-hover">
								<thead>
								<tr>
									<th>رقم التذكرة</th>
									<th>أسم المريض</th>
									<th>العمر</th>
									<th>العنوان</th>
									<th>تاريخ الحالة</th>
								</tr>
								</thead>
								<tbody>
									@if(isset($data))
										@foreach($data as $row)
											<tr>
												<td>{{ $row->ticket_number }}</td>
												<td>{{ $row->name }}</td>
												<td>{{ calculateAge($row->birthdate) }}</td>
												<td>{{ $row->address }}</td>
												<td>{{ \Carbon\Carbon::parse($row->closed_date)->format('Y-m-d')	 }}</td>
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
	
	<script>
		$(document).ajaxStart(function(){
				$("#overlay").show();
		});
		$(document).ajaxComplete(function(){
				$("#overlay").hide();
		});
		
		clinic_sl= $('#clinic');
		$(function(){
			
			getDepartmentDoctor(clinic_sl.val());

			$('#clinic').change(function(){
				getDepartmentDoctor(clinic_sl.val())
			});
		});

		function getDepartmentDoctor(clinic){
			url = " {{ url('patients/getDepartmentDoctors') }}";
			$.ajax({
				type: "POST",
				url: url,
				data: { 'mid':clinic },
				success: function (data) {
					if(data.success == "true"){
						$("#doctor_select option").remove();
						for (x in data.data) {
							$("#doctor_select").append(`<option value=`+data.data[x].id+`>`+data.data[x].name+`</option>`);								
						}
					}
				}
			});
		}
	</script>

@stop
