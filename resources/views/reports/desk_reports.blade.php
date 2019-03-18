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
@if(count($data))
  <?php $all_deps=false;?>
  @foreach($data as $visits_row)
    
    @if(count($visits_row) > 0)
    <table class="table table-striped table-bordered " style="direction: rtl;" >
  	    <tr>
          <th>رقم التسلسل</th>
          <th>رقم التذكرة</th>
          <th>نوع التذكرة</th>
          <th>التاريخ و الوقت</th>
          <th>أسم المريض</th>
      		<th>النوع</th>
      		<th>العمر</th>
      		<th>{{ $medical_type =='c'?'أسم العيادة':'أسم القسم' }}</th>
          @if(!isset($today_date))
      		  <th>{{ $medical_type =='c'?' تاريخ الحجز':'تاريخ الدخول' }}</th>
          @endif
			    <th>ملاحظات</th>
      	</tr>
      	@foreach($visits_row as $row)
          @if( $all_deps )
            <?php $all_deps=$row->all_deps;?>
            @continue
          @endif
      	<tr>
          <td>{{$row->serial_number}}</td>
          <td>{{$row->ticket_number}}</td>
          <td>{{$row->ticket_type!=null?($row->ticket_type=="G"?'استقبال عام':'استقبال اصابات'):""}}</td>
          <td>{{$row->registration_datetime}}</td>
      		<td>{{$row->patient->name}}</td>
      		<td>{{$row->patient->gender=='M'?'ذكر':'أنثى'}}</td>
      		<td> {{ calculateAge($row->patient->birthdate) }}</td>
      		<td>
            @if($row->all_deps)
              إستكشاف طاريء
            @else
             {{ $row->medicalunits[0]->name}}
            @endif
          <?php $all_deps=$row->all_deps;?>
          </td>
          @if(!isset($today_date))
      		  <td>{{date($row->created_at->format('Y-m-d'))}}</td>
          @endif
			    <th>@if($row->ticket_type=="") تم التحويل من مكتب حجز التذاكر @endif</th>
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
