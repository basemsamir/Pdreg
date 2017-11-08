<html>
<head>
	<meta charset="utf-8">
<style>
body{size: 21cm 29.7cm;
    margin: 30mm 45mm 30mm 45mm;}
	td,th{padding-top:10px; padding-bottom: 10px; padding-right: 30px;text-align: right;}
	th{width: 90px;}
	.head{font-weight: bold}
	table {border:1px solid black;width: 21cm;
    height: 150px; direction:rtl  }
	p{padding-top: 20px;}
	.secondtablecell{padding-right: 50px;}
	body{margin: 0 auto}
</style>
<title>بيانات ملف المريض</title>
</head>
<body onload="window.print()" > <br>
<h2 align ="center">بيانات ملف المريض</h2>
@if(count($visit) == 0)
	 <p align ="center" > لا يوجد بيانات </p>
@else

<table align=center >
	<tr>
		<th>الأســــــــــــم : </th><td>{{$patient_name}}</td>
	</tr>
	<tr>
		<th>القســــــــــــم : </th><td>{{$visit_department[0]->name}}</td>
	</tr>
	<tr>
		<th>رقـــــم الغرفة : </th><td>{{$visit->room_number}}</td>
	</tr>
	<tr>
		<th>تـاريخ الدخول :</th><td>{{$visit->entry_date}}</td>
	</tr>
	<tr>
		<th>تاريخ الخروج :</th><td >{{$visit->exit_date}}</td>
	</tr>
	<tr>
		<th>رقــــــم الملف :</th><td>{{$visit->file_number}}</td>
	</tr>
	<tr>
		<th>نـــــوع العلاج :</th><td>{{$visit_cure_name}}</td>
	</tr>
	<tr>
		<th>جهة التعــــاقد :</th><td>{{$visit->contract}}</td>
	</tr>
	
</table>

@endif
</body>
</html>
