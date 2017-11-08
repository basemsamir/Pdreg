<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$table_header}}</title>
  <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.css')}}">
  <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/font-awesome.min.css')}}">
	<style>
		.table th,.table td{text-align:center}
	</style>
</head>
<body>
<h3 align ="center"><b>{!! $table_header !!}</b></h3><br>
<h4 align="center"><b>الأسم:</b> {{$patient->name}} <b>&nbsp;&nbsp;&nbsp;&nbsp; النوع:</b> {{$patient->gender=='M'?'ذكر':'أنثي'}}
&nbsp;&nbsp;&nbsp;&nbsp; السن:</b> 
		<?php
			$current_date = new DateTime();
			$birthdate = new DateTime($patient->birthdate);
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
		  {{ $interval->d }}
		  @if( $interval->d > 11 )
			{{ "يوم" }}
		  @else
			{{ "أيام" }}
		  @endif
		@endif
</h4>
<br>
@if(count($data) > 0)
<table class="table table-striped table-bordered " style="direction: rtl;" >
	<tr >
		<th>تاريخ تسجيل المريض</th>
		<th>اسم العيادة / اسم القسم</th>
		<th>الشكوى</th>
		<th>التشخيص</th>
		<th>الأدوية / العلاج</th>
		<th>المستلزمات</th>
	</tr>
	@foreach($data as $row)
	<tr>
		<td>{{$row->created_at}}</td>
		<td>{{ str_replace(',',' ثم تم التحويل الي ', $row->medical_unit) }}</td>
		<td>{{$row->v_com}}</td>
		<td>{{$row->v_dia}}</td>
		<td>@if($row->v_med != ""){{ $row->v_med }}@endif <br>
			@if($row->v_cure != ""){{ $row->v_cure }}@endif
		</td>
		<td>@if($row->v_access_clinic != ""){{ $row->v_access_clinic }}@endif <br>
			@if($row->v_access_dep != ""){{ $row->v_access_dep }}@endif</td>
	</tr>
	@endforeach
</table>
@else
	<p style="text-align: center;font-size:25px">لا يوجد بيانات</p>
@endif
</body>
</html>