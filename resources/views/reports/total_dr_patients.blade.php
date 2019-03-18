<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$title}}</title>
  <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.css')}}">
  <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/font-awesome.min.css')}}">
	<style>
		.table th,.table td{text-align:center}
    h3{direction: rtl; text-align: center; font-weight: bold}
	</style>
</head>
<body onload="window.print()">
  <h3>{!! $table_header !!}</h3>
  @if(isset($data) and count($data) > 0)
  <table class="table table-striped table-bordered " style="direction: rtl;" >
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
          @foreach($data as $row)
            <tr>
              <td>{{ $row->ticket_number }}</td>
              <td>{{ $row->name }}</td>
              <td>{{ calculateAge($row->birthdate) }}</td>
              <td>{{ $row->address }}</td>
              <td>{{ \Carbon\Carbon::parse($row->closed_date)->format('Y-m-d')	 }}</td>
            </tr>
          @endforeach
    </tbody>
  </table>
  @else
	  <p style="text-align: center;font-size:25px">لا يوجد بيانات اليوم</p>
  @endif
</body>
</html>
