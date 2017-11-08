<html>
<head>
	<meta charset="utf-8">
<style>
body{size: 21cm 29.7cm;
    margin: 30mm 45mm 30mm 45mm;}
	td,th{padding-top:10px; padding-bottom: 10px; padding-right: 30px;text-align: right;}
	th{width: 99px;}
	.head{font-weight: bold}
	table {border:1px solid black;width: 21cm;
    height: 150px; direction:rtl  }
	p{padding-top: 20px;}
	.secondtablecell{padding-right: 50px;}
	body{margin: 0 auto}
</style>
<title>أذن دخول مريض</title>
</head>
<body onload="window.print()"> <br>
<h2 align ="center">أذن دخول مريض</h2>
@if(count($data) == 0)
	 <p align ="center" > لا يوجد بيانات </p>
@else

<table align=center >
	<tr>
		<th>ساعة الدخول : </th>
		<td>
			@if (strrpos($data[0]->entry_time,"AM") !== false)
				{{ str_replace('AM', 'ص', $data[0]->entry_time) }}
			@elseif (strrpos($data[0]->entry_time,"PM") !== false)
				{{ str_replace('PM', 'م', $data[0]->entry_time) }}
			@endif
		</td>
		<th>تاريخ الدخــول : </th><td>{{$data[0]->entry_date}}</td>
	</tr>
	<tr>
		<th> رقــــم الغرفة : </th><td>{{$data[0]->room_number}}</td>
		<th> الطبيب المعالج : </th><td>{{$data[0]->reference_doctor_name}}</td>
	</tr>
</table>
<br><br>
<table align=center >
	<tr><th colspan="6" style="text-align:center">بيانات خاصة بالمريض</th></tr>
	<tr>
		<th>الأســــــــــــم : </th><td>{{$data[0]->pname}}</td>
		<th>النـــــــــــــــــــوع : </th><td>{{$data[0]->gender=='M'?'ذكر':'أنثى'}}</td>
		<th>الســــــــــــــــــن : </th>
		<td>
			<?php
                $current_date = new DateTime();
                $birthdate = new DateTime($data[0]->birthdate);
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
		</td>
		
	</tr>
	<tr>
		<th>العنـــــــــوان :</th><td colspan="5">{{$data[0]->paddress}}</td>
	</tr>
	<tr>
		<th>رقم التليفون :</th><td>{{$data[0]->ppnumber}}</td>
		<th>الحالة الاجتماعية :</th><td>{{$data[0]->social_status}}</td>
		<th>المهنــــــــــــــــة :</th><td>{{$data[0]->pjob}}</td>
	</tr>
	<tr>
		<th>الرقم القومي :</th><td colspan="5">{{$data[0]->psid}}</td>
	</tr>
	<tr>
		<th>محول بواسطة :</th><td colspan="5">{{$data[0]->converted_by_doctor}}</td>
	</tr>
</table>
<p>
<table align=center>
	<tr ><th  colspan="6" style="text-align: center;">بيانات خاصة بمرافق المريض</th></tr>
	<tr>
		<th>الأســــــــــــم : </th><td colspan="2">{{$data[0]->c_name}}</td>
		<th>درجة القرابة : </th><td colspan="2">{{$data[0]->rel_name}}</td>
	</tr>
	<tr>
		<th>محــل الاقامة : </th><td colspan="5">{{$data[0]->rel_address}}</td>
	</tr>
	<tr>
		<th>رقم التليفون :</th><td colspan="2">{{$data[0]->rel_phone}}</td>
		<th>المهنــــــــــة :</th><td colspan="2">{{$data[0]->rel_job}}</td>
	</tr>
	<tr>
		<th>الرقم القومي :</th><td colspan="5">{{$data[0]->rel_sid}}</td>
	</tr>
</table>
</p>
@endif
</body>
</html>
