@extends('layouts.app')
@section('title')
تقرير المحولون من حجز العيادة الي الأستقبال
@endsection
@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">تقرير المحولون من حجز العيادة الي الأستقبال</li>
      </ol>
	  <h1>
        تقرير المحولون من حجز العيادة الي الأستقبال
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
							<a href="{{ url('admin/print_rec_to_desk') }}" target="_blank" class="btn btn-success @if(!Session::has('print_determined_date') && !Session::has('print_data_fromdate')) disabled @endif "><i class="fa fa-print"></i> طباعة التقرير</a>
						
							<div class="form-group">
								<button type="submit" value="reload" name="submit" class="btn btn-info"><i class="fa fa-search"></i> بحث جديد </button>
							</div>
						</div>
					 </div>
					 <div class="col-lg-4">
						 <div class="form-group">
  							<div class="iradio">
  								<input type="radio" name="duration" id="duration2"> <label for="duration[2]">تاريخ معين</label>
  							</div>
  							&nbsp;&nbsp;&nbsp;&nbsp;
  							{!! Form::text('determined_date',null,array('id'=>'datepicker3','disabled'=>'disabled','class'=>'form-control')) !!}
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
									<th>العمر</th>
									<th>أسم العيادة</th>

									<th>تاريخ الحجز</th>
									<th>أسم مدخل البيان</th>
									<th>مكتب الأستقبال المحول اليه</th>
								</tr>
								</thead>
								<tbody>
								@if(isset($data))
									@foreach($data as $row)
										<tr>
										  <td>{{$row->ticket_number}}</td>
										  <td>{{$row->id}}</td>
										  <td>{{$row->name}}</td>
										  <td>{{$row->gender=='M'?'ذكر':'أنثي'}}</td>
										  <td>
											<?php
												$current_date = new DateTime();
												$birthdate = new DateTime($row->birthdate);
												$interval = $current_date->diff($birthdate);
											?>
											@if($interval->y > 0)
											  {{ $interval->y }}
											  @if( $interval->y > 11 )
												{{ "سنة" }}
											  @else
												{{ "سنوات" }}
											  @endif
											@elseif($interval->m > 0)
											  {{ $interval->m }}
											  @if( $interval->m > 11 )
												{{ "شهر" }}
											  @else
												{{ "شهور" }}
											  @endif
											@else
											  {{ $interval->d." يوم " }}
											  @if( $interval->d > 11 )
												{{ "يوم" }}
											  @else
												{{ "أيام" }}
											  @endif
											@endif
										  </td>
										  <td>{{$row->dept_name}}</td>
					
										  <td>{{date($row->created_at->format('Y-m-d'))}}</td>
										  <td>{{$row->user_name}}</td>
										  <td>{{$row->entry_name}}</td>

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
});
</script>
@stop
