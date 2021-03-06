@extends('layouts.app')
@section('title')
تقرير بيانات مرضى الدخول خلال فترة
@endsection
@section('content')


<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
			<h1>
			تقرير بيانات مرضى الدخول خلال فترة
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">تقرير بيانات مرضى الدخول خلال فترة</li>
      </ol>
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
						</div>
						<div class="form-group">

							<a href="{{ url('admin/printinpatient') }}" target="_blank" class="btn btn-success @if(!Session::has('receiptionists') && !Session::has('receiptionist')) disabled @endif"><i class="fa fa-print"></i> طباعة التقرير </a>

							<div class="form-group">
								<button type="submit" value="reload" name="submit" class="btn btn-info"><i class="fa fa-search"></i> بحث جديد </button>
							</div>
						</div>
					 </div>
					 <div class="col-lg-4">
					    <div class="form-group">
						  {!! Form::label('أسم مدخل البيان') !!}
						  {!! Form::select('reception_name',$reception_users,null,['id'=>'reception_name','class' => 'form-control','disabled']); !!}
						  <div class="checkbox icheck">
							<label>
							  <input type="checkbox" id="show_all" checked />
							  عرض الكل
							</label>
						  </div>
						</div>
					 </div>
					 <div class="col-lg-4">
					  <div class="form-group">
							<div class="iradio checked">
								<input type="radio" name="duration" id="duration" checked> <label for="duration[1]">فترة</label>
							</div>
							{!! Form::label('من') !!}
							{!! Form::text('fromdate',null,array('id'=>'datepicker','class'=>'form-control')) !!}
							<br>
							{!! Form::label('الي') !!}
						  {!! Form::text('todate',null,array('id'=>'datepicker2','class'=>'form-control')) !!}
						</div>
						<br><br>
						<div class="form-group">
							<div class="iradio">
								<input type="radio" name="duration" id="duration2"> <label for="duration[2]">تاريخ معين</label>
							</div>
							&nbsp;&nbsp;&nbsp;&nbsp;
							{!! Form::text('determined_date',null,array('id'=>'datepicker3','disabled'=>'disabled','class'=>'form-control')) !!}
						</div>
					 </div>

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
									<th>رقم التذكرة</th>
									<th>كود المريض</th>
									<th>أسم المريض</th>
									<th>النوع</th>
									<th>أسم القسم</th>
									<th>ساعة دخول المريض</th>
									<th>تاريخ دخول المريض</th>
									<th>أسم المرافق</th>
									<th>درجة القرابة</th>
									<th>رقم بطاقة المرافق</th>
									<th>عنوان المرافق</th>
									<th>أسم مدخل البيان</th>
								</tr>
								</thead>
								<tbody>
								@if(isset($visits_user))
									@foreach($visits_user as $visits_row)
										@foreach($visits_row as $row)
										<tr>
											<td>{{$row->ticket_number=='0'?'لا يوجد تذكرة':$row->ticket_number}}</td>
											<td>{{$row->id}}</td>
											<td>{{$row->name}}</td>
											<td>{{$row->gender=='M'?'ذكر':'أنثى'}}</td>
											<td>{{$row->dept_name}}</td>
											<td>
												@if (strrpos($row->entry_time,"AM") !== false)
													{{ str_replace('AM', 'ص', $row->entry_time) }}
												@elseif (strrpos($row->entry_time,"PM") !== false)
													{{ str_replace('PM', 'م', $row->entry_time) }}
												@endif
											</td>
											<td>	
												@if(isset($row->entry_date))
													{{$row->entry_date}}
												@else
													{{$row->created_at->format('Y-m-d')}}
												@endif
											</td>
											
											<td>{{$row->c_name}}</td>
											<td>{{$row->rel_name}}</td>
											<td>{{$row->c_sid}}</td>
											<td>{{$row->address}}</td>
											<td>{{$row->user_name}}</td>
										</tr>
										@endforeach
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
	$("#datepicker3").val("");
	$("#show_all").on('ifChanged',function(){
		if($("#reception_name").attr("disabled"))
			$("#reception_name").removeAttr("disabled");
		else
			$("#reception_name").attr("disabled","disabled");
	});
	$("#duration").on('ifChanged',function(){
		if($("#duration").val() == "on"){
			$("#datepicker").removeAttr("disabled");
			$("#datepicker2").removeAttr("disabled");
			$("#datepicker3").attr("disabled",true);
			$("#datepicker3").val("");
		}
		else{
			$("#datepicker").attr("disabled",true);
			$("#datepicker2").attr("disabled",true);
			$("#datepicker").val("");
			$("#datepicker2").val("");
			$("#datepicker3").removeAttr("disabled");
		}
	});
	$("#duration2").on('ifChanged',function(){
		if($("#duration2").val() == "on"){
			$("#datepicker").attr("disabled",true);
			$("#datepicker2").attr("disabled",true);
			$("#datepicker").val("");
			$("#datepicker2").val("");
			$("#datepicker3").removeAttr("disabled");
		}
		else{
			$("#datepicker").removeAttr("disabled");
			$("#datepicker2").removeAttr("disabled");
		  $("#datepicker3").attr("disabled",true);
		  $("#datepicker3").val("");
		}
	});
});
</script>
@stop
