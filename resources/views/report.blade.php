<html>
<head>
	<meta charset="utf-8">
<style>
@font-face {
    font-family: IDAutomationHC39M;
    src: url(http://www.idautomation.com/barcode-fonts/woff-web-fonts/IDAutomationHC39M.woff);
    }
body{size: 21cm 29.7cm;
    margin: 30mm 45mm 30mm 45mm;}
	td{padding-top:20px; padding-bottom: 10px; padding-right: 30px;    text-align: right;}
	.head{font-weight: bold}
	table {border:1px solid black }
	p{padding-top: 20px;}
	.secondtablecell{padding-right: 50px;}
	body{margin: 0 auto}
</style>
<title>
تقرير
</title>
</head>
<body onload="window.print()"> <br>
<h2 align ="center">تقرير دخول المريض</h2>

@if(count($data) == 0)
	 <p align ="center" > لا يوجد بيانات </p>
@else
<p align ="center"> <b>تاريخ الدخول : </b>{{$data[0]->created_at->format('d-m-Y')}} <b>ساعة الدخول :</b>
	<?php
			if (strrpos($data[0]->entry_time,"AM") !== false) {
					echo str_replace('AM', 'ص', $data[0]->entry_time);
			}
			else if (strrpos($data[0]->entry_time,"PM") !== false) {
					echo str_replace('AM', 'م', $data[0]->entry_time);
			}
	?>
	</p>
<p align ="center"> <b>سبب دخول المريض : </b> {{$data[0]->entry_reason_desc}} </p>

<table align=center style=" width: 21cm;
    height: 150px;">
	<tr ><td colspan="4" align="right"><h4> بيانات خاصة بالمريض</h4></td></tr>
<tr ><td>{{$data[0]->gender=='M'?'ذكر':'أنثى'}}</td><td class="head">النوع</td><td>{{$data[0]->birthdate}}</td><td align="right" class="head">تاريخ الميلاد</td><td>{{$data[0]->pname}}</td><td class="head">الاسم</td></tr>
<!--<tr ><td colspan="3">{{$data[0]->nationality}}</td><td class="head">الجنسية</td><td>{{$data[0]->pjob}}</td><td align ="right" class="head" >المهنة</td></tr> -->
<tr ><td colspan="5">{{$data[0]->paddress}}</td><td class="head">العنوان</td></tr>
<!-- <tr ><td>{{$data[0]->ppnumber}}</td><td class="head">رقم التليفون</td><td>{{$data[0]->issuer}}</td><td class="head">جهة صدورها</td><td>{{$data[0]->psid}}</td><td class="head">رقم البطاقة</td></tr> -->
<tr> <td colspan="5">{{$data[0]->psid}}</td><td class="head">رقم البطاقة</td> </tr>
<tr ><td colspan="5">{{$data[0]->ticket_number}}</td><td class="head">رقم التذكرة</td></tr>
</table>
<p>
<table align=center style=" width: 21cm;
    height: 150px;">
	<tr ><td  colspan="4" style="text-align: center;"><h4>بيانات خاصة بمرافق المريض</h4></td></tr>
	<tr align ="center"><td>{{$data[0]->rel_name}}</td><td  class="head">درجة القرابة</td><td>{{$data[0]->c_name}}</td><td  class="head" class="secondtablecell">الاسم</td></tr>
	<tr align ="center">  <td></td><td  class="head"></td> <td>{{$data[0]->rel_address}}</td><td  class="head" class="secondtablecell">محل الاقامة</td></tr>
	<tr align ="center"> <td></td><td  class="head"></td> <td>{{$data[0]->rel_job}}</td><td  class="head" class="secondtablecell">المهنة</td></tr>
	<tr align ="center"><td></td><td  class="head"></td><td>{{$data[0]->rel_sid}}</td><td  class="head" class="secondtablecell"> البطاقة</td></tr>
</table>
</p>
@endif
</body>
</html>
