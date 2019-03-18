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
<body onload="window.print()">
<h3 align ="center"><b>{!! $table_header !!}</b></h3>
<br>
@if(count($data) > 0)
<?php $i=1; ?>
<table class="table table-striped table-bordered " style="direction: rtl;" >
		<thead>
			<tr>
				<th>رقم التذكرة</th>
				<th>كود المريض</th>
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
						<td>{{ $row->id }}</td>
						<td>{{ $row->name }}</td>
						<td>{{ calculateAge($row->birthdate) }}</td>
						<td>{{ $row->address }}</td>
						<td>{{ \Carbon\Carbon::parse($row->created_at)->format('Y-m-d')	 }}</td>
					</tr>
				@endforeach
			@endif
		</tbody>
</table>

@else
	<p style="text-align: center;font-size:25px">لا يوجد بيانات</p>
@endif
</body>
</html>