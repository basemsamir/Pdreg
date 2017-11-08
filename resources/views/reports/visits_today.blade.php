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
@if(count($data) && isset($numberOfVisits) && $numberOfVisits > 0)
  @foreach($data as $visits_row)
    @if(count($visits_row) > 0)
		@if(!isset($role_name))
			<h4 align ="center"><b>أسم مدخل البيان : {{ $visits_row[0]->user_name }}</b></h4>
		@endif
    <table class="table table-striped table-bordered " style="direction: rtl;" >
  	    <tr>
          @if($medical_type =='c')
            <th>رقم التذكرة</th>
          @endif
		 @if(isset($role_name) && $role_name =='Desk')
            <th>نوع التذكرة</th>
          @endif
      		<th>كود المريض</th>
      		<th>أسم المريض</th>
      		<th>النوع</th>
      		<th>العمر</th>
          <th>العنوان</th>
      		<th>{{ $medical_type =='c'?'أسم العيادة':'أسم القسم' }}</th>
          @if(!isset($today_date))
      		  <th>{{ $medical_type =='c'?' تاريخ الحجز':'تاريخ الدخول' }}</th>
          @endif
		  @if(isset($role_name) && $role_name == "Desk")
			  <th>ملاحظات</th>
	      @endif
      	</tr>
      	@foreach($visits_row as $row)
      	<tr>
          @if($medical_type =='c')
            <td>{{$row->ticket_number}}</td>
          @endif
		  @if(isset($role_name) && $role_name =='Desk')
            <td>{{$row->ticket_type!=null?($row->ticket_type=="G"?'استقبال عام':'استقبال اصابات'):""}}</td>
          @endif
      		<td>{{$row->id}}</td>
      		<td>{{$row->name}}</td>
      		<td>{{$row->gender=='M'?'ذكر':'أنثى'}}</td>
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
          <td>{{$row->address}}</td>
      		<td>{{$row->dept_name}}</td>
          @if(!isset($today_date))
      		  <td>{{date($row->created_at->format('Y-m-d'))}}</td>
          @endif
		   @if(isset($role_name) && $role_name == "Desk")
			  <th>@if($row->ticket_type=="") تم التحويل من مكتب حجز التذاكر @endif</th>
	      @endif
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