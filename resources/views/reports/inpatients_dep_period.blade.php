<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>تقرير بحصر دخول مرضى القسم الداخلي</title>
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
<body onload="window.print()">
<h3 align ="center"><b>{!! $table_header !!}</b></h3>
@if(count($data) && isset($numberOfVisits) && $numberOfVisits > 0)
  @foreach($data as $visits_row)
    @if(count($visits_row) > 0)
    <h4 align ="center"><b>أسم مدخل البيان : {{ $visits_row[0]->user_name }}</b></h4>
    <table class="table table-striped table-bordered " style="direction: rtl;" >
    <tr>
     <th>أسم المريض</th>
	 <th>التشخيص النهائي</th>
	 <th>تاريخ الدخول</th>
	 <th>تاريخ الخروج</th>
	 <th>عدد الايام</th>
	 <th>أسم مدخل البيان</th>
    </tr>
      @foreach($visits_row as $row)
    	<tr>
        <td>{{$row->name}}</td>
		<td>{{$row->fd}}</td>
		<td>{{$row->ed}}</td>
		<td>{{$row->exd}}</td>
		<td>
			<?php 
				$exit_date = new DateTime($row->exd);
				$entry_date = new DateTime($row->ed);
				$interval = $exit_date->diff($entry_date);
			?>
			{{$interval->d}}
		</td>
		<td>{{$row->user_name}}</td>
    	</tr>
    	@endforeach
    </table>
    <br>
    @else
      @continue
    @endif
  @endforeach
  @if(isset($numberOfVisits))
		<p style="margin-left: 15px"><b> أجمالي عدد الحالات : {{$numberOfVisits}} </b></p>
	@endif
@else
	<p style="text-align: center;font-size:25px">لا يوجد بيانات اليوم</p>
@endif

</body>
</html>
