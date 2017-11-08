<html>
<head>
    <meta charset="utf-8">
	 <link rel="stylesheet" href="{!! asset('bootstrap/fonts/Code39Azalea.css') !!}">
	 
    <title>
        كارت المريض
    </title>
<body onload="window.print();">
<table align="center">
    <tr align="right"><td align="right">{{$data['name']}}</td><td align = right class="datatitle">  اسم المريض</td></tr>
    <tr align="right"><td align="right">{{$data['id']}}</td><td align = right class="datatitle"> رقم المريض</td></tr>
    <tr align="right"><td align="right">{{$data['birthdate']}}</td><td align = right class="datatitle">  تاريخ الميلاد</td></tr>
    <tr ><td colspan="2"style="padding-left:10px;font-family: Code39AzaleaFont;font-size:55px;text-align:center;">*{{$data['id']}}*</td></tr>
</table>
<br>

</body>
</head>
</html>