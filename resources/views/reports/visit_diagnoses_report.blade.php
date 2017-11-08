<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>بيانات تشخيص حالة المريض</title>
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
<body onload="window.print()" > <br>
<h3 align ="center"><b>بيانات تشخيص حالة المريض</b></h3><br>
<h4 align ="center">أسم المريض: {{$patient_name}}</h4>
@if(count($visit) == 0)
	 <p align ="center" > لا يوجد بيانات </p>
@else
<br>
<table class="table table-striped table-bordered " style="direction: rtl;" >
    <tr>
     <th>تاريخ الزيارة الطبيب</th>
     <th>التشخيص ( عربي )</th>
	 <th>التشخيص ( أنجليزي )</th>
	 <th>العلاج</th>
	 <th>أسم الطبيب</th>
    </tr>
	@foreach($visit_diagnoses as $row)
	<tr>
		<td>{{$row->created_at->format('Y-m-d')}}</td>
		<td>{{$row->content}}</td>
		<td>{{$row->content_in_english}}</td>
		<td>{{$row->cure_description}}</td>
		<td>{{$row->name}}</td>
	</tr>
	@endforeach
</table>

@endif
</body>
</html>
