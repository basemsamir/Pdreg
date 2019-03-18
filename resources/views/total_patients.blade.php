@extends('layouts.app')
@section('title')
تقرير إجمالي عدد الحالات خلال فترة
@endsection
@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">تقرير إجمالي عدد الحالات خلال فترة</li>
      </ol>
	  <h1>
        تقرير إجمالي عدد الحالات خلال فترة
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
					{!! Form::open(array('class'=>'form-inline')) !!}
					 <div class="col-lg-4">
						<div class="form-group">
							<button type="submit" value="show_only" name="submit" class="btn btn-primary"><i class="fa fa-search"></i> عرض النتائج </button>
						</div>
						<div class="form-group">
							<a href="{{ url('admin/print_total_patients') }}" target="_blank" class="btn btn-success @if(!Session::has('print_determined_date') && !Session::has('print_data_fromdate')) disabled @endif "><i class="fa fa-print"></i> طباعة التقرير</a>
						
							<div class="form-group">
								<button type="submit" value="reload" name="submit" class="btn btn-info"><i class="fa fa-search"></i> بحث جديد </button>
							</div>
						</div>
					 </div>
					 <div class="col-lg-2">
						 <div class="form-group">
  							<div class="iradio">
  								<input type="radio" name="duration" id="duration2"> <label for="duration[2]">تاريخ معين</label>
  							</div>
  							{!! Form::text('determined_date',null,array('id'=>'datepicker3','disabled'=>'disabled','class'=>'form-control')) !!}
  						</div>
					 </div>
					 <div class="col-lg-3">
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
					 </div>
					 <div class="col-lg-3">
						<div class="form-group">
 							<div class="iradio checked">
 								<input type="radio" name="reservation_type" id="reservation_type" checked value="c"> <label for="reservation_type[1]">عيادات</label>
								<input type="radio" name="reservation_type" id="reservation_type1" value="d"> <label for="reservation_type[1]">أستقبال</label>
								<input type="radio" name="reservation_type" id="reservation_type2" value="e"> <label for="reservation_type[1]">داخلي</label>
							</div>
							<br>
							<div id="clinic_div">
								{!! Form::label('أسم العيادة') !!}
								<select name="clinic" id="" class="form-control">
									<option value="">الكل</option>
									@foreach($clinics as $clinic)
										<option value="{{$clinic->id}}">{{$clinic->name}}</option>
									@endforeach
								</select>
							</div>
							<div id="dep_div" style="display:none">
								{!! Form::label('أسم القسم') !!}
								<select name="department" id="" class="form-control">
									<option value="">الكل</option>
									@foreach($deps as $dep)
										<option value="{{$dep->id}}">{{$dep->name}}</option>
									@endforeach
								</select>
							</div>
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
								  <th>م</th>
									<th>أسم {{ $reservation_type == 'c'?'العيادة':'القسم' }}</th>
									<th>عدد حالات {{ $reservation_type == 'c'?'العيادة':'القسم' }}</th>
								</tr>
								</thead>
								<tbody>
								@if(isset($data))
									<?php $i=1; ?>
									@foreach($data as $row)
									<tr>
									  <td>{{$i++}}</td>
									  <td>{{$row->name}}</td>
									  <td>{{$row->numberOfVisits}}</td>
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
	$("#datepicker3").val("");
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

	$("#reservation_type").on('ifChanged',function(){
		if($("#clinic_div").css('display') == "none"){
				$("#clinic_div").toggle();
				$("#dep_div").toggle();
			}
	});
	$("#reservation_type1").on('ifChanged',function(){
			if($("#clinic_div").css('display') == "none"){
				$("#clinic_div").toggle();
				$("#dep_div").toggle();
			}
	});
	$("#reservation_type2").on('ifChanged',function(){
			$("#clinic_div").toggle();
			$("#dep_div").toggle();
	});
});
</script>
@stop
