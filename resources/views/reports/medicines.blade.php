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
@if(count($data) > 0)
    <table class="table table-striped table-bordered " style="direction: rtl;" >
  	    <tr>
          <th>أسم المريض</th>
          <th>الأدوية</th>
          <th>المستلزمات</th>
      	</tr>
          @foreach($data as $row)
            <tr>
              <td>{{$row->name}}</td>
              <td>
                {{$row->v_cure}} @if($row->v_cure != "")<br>@endif
                {{$row->v_med}}
              </td>
              <td>
                {{$row->v_access_clinic}}@if($row->v_cure != "") <br>@endif
                {{$row->v_access_dep}}
              </td>
            </tr>
          @endforeach

    </table>
@else
	<p style="text-align: center;font-size:25px">لا يوجد بيانات اليوم</p>
@endif

</body>
</html>
