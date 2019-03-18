<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>تقرير مرضى الدخول</title>
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
<h3 align ="center"  style="direction: rtl;"><b>{!! $table_header !!}</b></h3>
@if(count($data) && isset($numberOfVisits) && $numberOfVisits > 0)
  @foreach($data as $visits_row)
    @if(count($visits_row) > 0)
    <h4 align ="center" style="direction: rtl;"><b>أسم مدخل البيان : {{ $visits_row[0]->user_name }}</b></h4>
    <table class="table table-striped table-bordered " style="direction: rtl;" >
    <tr>
      <th>رقم التذكرة</th>
	  @if(isset($role_name) && $role_name == "Desk")
		  <th>نوع التذكرة</th>
	  @endif
      <th>كود المريض</th>
      <th>أسم المريض</th>
      <th>النوع</th>
      <th>أسم القسم</th>
      <th>تاريخ دخول المريض</th>
      <th>ساعة دخول المريض</th>
      <th>أسم المرافق</th>
      <th>درجة القرابة</th>
      <th>رقم بطاقة المرافق</th>
      <th>عنوان المرافق</th>
    </tr>
      @foreach($visits_row as $row)
    	<tr>
        <td>{{$row->ticket_number=='0'?'لا يوجد تذكرة':$row->ticket_number}}</td>
		 @if(isset($role_name) && $role_name == "Desk")
		  <td>{{$row->ticket_type=="G"?"استقبال عام":"استقبال اصابات"}}</td>
	     @endif
    		<td>{{$row->id}}</td>
    		<td>{{$row->name}}</td>
    		<td>{{$row->gender=='M'?'ذكر':'أنثى'}}</td>
    		<td>{{$row->dept_name}}</td>
			@if(isset($row->entry_date))
				<td>{{$row->entry_date}}</td>
			@else
				<td>{{$row->created_at->format('Y-m-d')}}</td>
			@endif
        <td>
            @if (strrpos($row->entry_time,"AM") !== false)
                {{ str_replace('AM', 'ص', $row->entry_time) }}
            @elseif (strrpos($row->entry_time,"PM") !== false)
                {{ str_replace('PM', 'م', $row->entry_time) }}
            @endif
        </td>
        <td>{{$row->c_name}}</td>
        <td>{{$row->rel_name}}</td>
        <td>{{$row->c_sid}}</td>
        <td>{{$row->address}}</td>
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
