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
		.main{
			border: 3px solid;
			border-radius:10px;
			padding: 10px 20px 100px 30px;
		}
		.main h4{
			text-align:center;
			
		}
		.main > div{
			direction: rtl;
		}
		.main table{
			width: 100%;

		}
		.main table td{
			text-align: right;
			padding-bottom: 15px;
			vertical-align: text-top;
		}
		.main table td{
			text-align: right;
			padding-bottom: 15px;
			vertical-align: text-top;
		}
		.main table td:nth-child(2){
			width: 43% !important;
		}
		.head{
			width: 19% !important;
			font-weight: bold;
			font-size: 15px;
		}
		.row{
			margin: 0;
		}
		.main table td:nth-child(3),.main table td:nth-child(4),.main table td:nth-child(5){
			width: 12% !important;
		}
		.log_container{
			margin-top: 15px;
		}
		.log_container{
			text-align: center
		}
		.log_container p{
			margin-bottom: 0;
			font-weight: bold
		}
		.log{
			width: 100px;
		}
	</style>
</head>
<body >
<div class="row">
	<div class="col-md-2 pull-right log_container">
		<img src="{{asset('images/logobig.png')}}" style="width: 50px"  alt="logo">
		<p>المستشفيات الجامعية</p>
		<p>مستشفى اسيوط الجديدة الجامعي</p>
	</div>
</div>

<h3 align ="center"><b>{!! $table_header !!}</b></h3>
<br>
@if(count($data))
<div id="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2 main">
			<h4><b> الي من يهمه الأمر</b></h4><br>
			<div>
				<table >
					<tr>
						<td class="head">أسم المريض : </td>
						<td>{{$data[0]->name}}</td>
						<td class="head">رقم المستشفى : </td>
						<td colspan="2" ></td>
					</tr>
					<tr>
						<td class="head">السن : </td>
						<td>{{calculateAge($data[0]->birthdate)}}</td>
						<td class="head">المهنة : </td>
						<td>{{$data[0]->p_job}}</td>
					</tr>
					<tr>
						<td class="head">@if(isset($department_flag)) تاريخ الدخول : @else تاريخ الكشف : @endif</td>
						<td>
						@if(isset($department_flag))
							@if(isset($data[0]->entry_date))
								{{ \Carbon\Carbon::parse($data[0]->entry_date)->format('d / m / Y')}}
							@endif
						@else
							{{ \Carbon\Carbon::parse($data[0]->created_at)->format('d / m / Y')}}
						@endif
						</td>
						<td class="head">القسم : </td>
						<td>{{$data[0]->clinic_name}}</td>
					</tr>
					<tr>
						<td class="head">
						@if(isset($department_flag))
							 تاريخ الدخول بالحروف : 
							 
						@else
							 تاريخ الكشف بالحروف : 
						@endif</td>
						<td colspan="3">
						</td>
					</tr>

					@if(isset($department_flag))
					<tr>
						<td class="head">تاريخ الخروج :</td>
						<td >@if($data[0]->exit_date)
								{{\Carbon\Carbon::parse($data[0]->exit_date)->format('d / m / Y')}}
							 @endif
					    </td>
						<td class="head">القسم : </td>
						<td>{{$data[0]->clinic_name}}</td>
					</tr>
					<tr>
						<td class="head">تاريخ الخروج بالحروف : </td>
						<td colspan="3">
						</td>
					</tr>
					@endif
					<tr>
						<td class="head">التشخيص : </td>
						<td colspan="3" rowspan="3">{{$data[0]->diagnoses}}</td>
					</tr>
					<tr>
						<td class="head"></td>
					</tr>
					<tr>
						<td class="head"></td>
					</tr>
					<tr>
						<td class="head">التوصية : </td>
						<td colspan="3" rowspan="2">{{$data[0]->doctor_recommendation}}</td>
					</tr>
					<tr>
						<td></td>
					</tr>
					<tr>
						<td></td>
					</tr>
				</table>
			</div>
			<br>
			<div class="row">
				<div class="col-md-6 pull-left">
					<h4><b>مدير التسجيل الطبي بالمستشفى</b></h4>
					<p></p>
				</div>
				<div class="col-md-offset-2 col-md-4 pull-right">
					<h4><b>الطبيب المختص</b></h4>
					<p></p>
				</div>
			</div>
			<br>
			<h4><b>يعتمد</b></h4><br>
		</div>
		
	</div>
	<h4 align="center"><b>ملحوظة: المستشفى غير مسئولة عن حركة المرافق داخل و خارج المستشفى</b></h4><br>
</div>
@else
	<p style="text-align: center;font-size:25px">لا يوجد بيانات</p>
@endif
</body>
</html>
