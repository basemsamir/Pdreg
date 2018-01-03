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
	  <h1>
       {{ $title }}
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
					@if($errors->any())
					<div class="alert alert-danger">
						@foreach($errors->all() as $error)
							<p><b>{{ $error }}</b></p>
						@endforeach
					</div>
					@endif
					<div class="row" >
					{!! Form::open(array('class'=>'form-inline','name'=>'patient_form')) !!}
					 <div class="col-lg-4">
						<div class="form-group">
							<button type="submit" value="show_only" name="submit" class="btn btn-primary"><i class="fa fa-search"></i> عرض النتائج </button>
							<button type="submit" value="reload" name="submit" class="btn btn-info"><i class="fa fa-search"></i> بحث جديد </button>
						</div>
					 </div>
					 
					 @include('medical_reports.search_form')

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
								<h4>{!! $header !!}</h4>
							@endif
							<table id="example1" class="table table-bordered table-hover">
								<thead>
								<tr>
									@if(!isset($department_flag))
									<th>رقم التذكرة</th>
									@endif
								  <th>@if(!isset($department_flag)) تاريخ و وقت الكشف @else تاريخ و وقت الدخول @endif</th>
									<th>كود المريض</th>
									<th>أسم المريض</th>
									<th>النوع</th>
									<th>العمر</th>
									<th>@if(!isset($department_flag))أسم العيادة @else أسم القسم @endif</th>
									<th>طباعة التقرير</th>
								</tr>
								</thead>
								<tbody>
									@if(isset($data))
										@foreach($data as $row)
											<tr>
												@if(!isset($department_flag))
												<td>{{ $row->ticket_number }}</td>
												@endif
												<td>@if(!isset($department_flag))
															{{ $row->created_at }}
														@else
															@if(!is_null($row->entry_date))
																{{ \Carbon\Carbon::parse($row->entry_date." ".$row->entry_time) }}
															@endif
														@endif
												</td>
												<td>{{ $row->pid }}</td>
												
												<td>{{ $row->name }}</td>
												<td>{{ $row->gender=="M"?'ذكر':'أنثى' }}</td>
												<td>{{ calculateAge($row->birthdate) }}</td>
												<td>{{ $row->category_name }}</td>
												<td align="center">
												<a href='@if(!isset($department_flag))
																	@if(isset($desk_type))
																		@if($desk_type == "g")
																			{{  url("admin/medicalreports/gdesk/{$row->id}/print") }}
																		@else
																			{{  url("admin/medicalreports/tdesk/{$row->id}/print") }}
																		@endif
																	@else
																		{{  url("admin/medicalreports/clinics/{$row->id}/print") }}
																	@endif
																 @else
																 		@if(!isset($desk_type))
																			{{ url("admin/medicalreports/entry_clinics/{$row->id}/print") }}
																		@else
																				@if($desk_type == "g")
																					{{  url("admin/medicalreports/entry_gdesk/{$row->id}/print") }}
																				@else
																					{{  url("admin/medicalreports/entry_tdesk/{$row->id}/print") }}
																				@endif
																		@endif
																 @endif'
													class="btn btn-primary" title="طباعة" target="_blank">
													<i class="fa fa-print"></i>
												</a>
												</td>
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
<script>

$(document).ready(function(){
	$("#datepicker").val("");
	$("#datepicker2").val("");
	$("#date_selection").change(function(){
		if($(this).val()=="date_selected"){
				$("#datepicker").removeAttr('disabled');
				$("#datepicker2").removeAttr('disabled');
		}
		else{
			$("#datepicker").attr('disabled',true);
			$("#datepicker2").attr('disabled',true);
		}
	});
});
</script>
@stop
