<html>
<head>
	<meta charset="utf-8">
<style>
body{size: 21cm 29.7cm;
    margin: 30mm 45mm 30mm 45mm;}
	td,th{padding-top:10px; padding-bottom: 10px; padding-right: 30px;text-align: right;}
	th{width: 120px;}
	.head{font-weight: bold}
	table {border:1px solid black;width: 21cm;
    height: 150px; direction:rtl  }
	p{padding-top: 20px;}
	.secondtablecell{padding-right: 50px;}
	body{margin: 0 auto}
</style>
<title>بيان خروج مريض</title>
</head>
<body onload="window.print()"> <br>
<h2 align ="center">بيان خروج مريض</h2>
@if(count($data) == 0)
	 <p align ="center" > لا يوجد بيانات </p>
@else

<table align=center >
	<tr>
		<th>الحالة عند الخروج :</th><td>{{$data[0]->es}}</td>
	</tr>
	<tr>
		<th> تاريخ الخروج :</th><td>{{$data[0]->ed}}</td>
	</tr>
	<tr>
		<th> التشخيص النهائي :</th><td>{{$data[0]->fd}}</td>
	</tr>
	<tr>
		<th> الطبيب المعالج :</th><td>{{$data[0]->name}}</td>
	</tr>
	<tr>
		<th> التوصية :</th><td>{{$data[0]->doctor_recommendation}}</td>
	</tr>
</table>
@endif
</body>
</html>
