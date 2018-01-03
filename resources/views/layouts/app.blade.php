<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('bootstrap/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('bootstrap/css/ionicons.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/AdminLTE.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{asset('dist/css/skins/_all-skins.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="{{asset('plugins/timepicker/bootstrap-timepicker.min.css')}}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{asset('plugins/datepicker/datepicker3.css')}}">

  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/jQueryUI/jquery-ui.css')}}">

  <noscript>
	<META HTTP-EQUIV="Refresh" CONTENT="0;URL={{ url('/JavascriptNotFound') }}">
  </noscript>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  @yield('css')
</head>
<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
		 @include('layouts.partial.header')
         @yield('content')
		 @include('layouts.partial.footer')
    </div>
	<!-- jQuery 2.2.3 -->
	<script src="{{asset('plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="{{asset('plugins/jQueryUI/jquery-ui.min.js')}}"></script>


	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
	  $.widget.bridge('uibutton', $.ui.button);
	</script>
	<!-- Bootstrap 3.3.6 -->
	<!-- iCheck -->
	<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
	<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
	<!-- DataTables -->
	<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
	<script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
	<script>
	$('document').ready(function(){
		$('input').iCheck({
		  checkboxClass: 'icheckbox_square-blue',
		  radioClass: 'iradio_square-blue',
		  increaseArea: '20%' // optional
		});

    $('.timepicker').timepicker({
      showInputs: false,
			minuteStep: 1
    })
		$("button[type='submit']").click(function(){
			 $("#overlay").show();
		});
    $("button[type='button']").click(function(){
			 $("#overlay").show();
		});
		$("#datepicker").datepicker(
			{format:"yyyy-mm-dd",
			 startDate: '-100y',
			 endDate: '+0d',
			 });
		$("#datepicker2").datepicker(
			{format:"yyyy-mm-dd",
			 startDate: '-100y',
			 endDate: '+0d'});
    $("#datepicker3").datepicker(
 			{format:"yyyy-mm-dd",
 			 startDate: '-100y',
 			 endDate: '+0d'});
		$('#example1').DataTable({
		  "bSort": false,
		  "paging": true,
		  "lengthChange": true,
		  "searching": false,
		  "info": true,
		  "autoWidth": false,
		});
		$('#example2').DataTable({
		  "bSort": false,
		  "paging": true,
		  "lengthChange": true,
		  "searching": true,
		  "info": false,
		  "autoWidth": false,
		});
		$('#example3').DataTable({
		  "bSort": false,
		  "paging": true,
		  "lengthChange": true,
		  "searching": true,
		  "info": false,
		  "autoWidth": false,
		});
		$('#example5').DataTable({
		  "bSort": false,
		  "paging": true,
		  "lengthChange": true,
		  "searching": false,
		  "info": false,
		  "autoWidth": false,

		});
		$('#visits_datatable').DataTable({
		  "bSort": false,
		  "paging": true,
		  "lengthChange": true,
		  "searching": true,
		  "info": true,
		  "autoWidth": false,
		});
		$("#datepicker").change(function(){
			var birthdate = new Date($("#datepicker").val());
			var today = new Date();
			var diffYears = today.getFullYear() - birthdate.getFullYear();
			var diffMonths = today.getMonth() - birthdate.getMonth();
			var diffDays = today.getDate() - birthdate.getDate();
			if(isNaN(diffDays)){
				$("#age").val('');
				$("#datepicker").val('هذا التاريخ غير صالح');
				return;
			}
			if(diffDays < 0){
				diffMonths--;
				diffDays+=30;
			}
			if(isNaN(diffMonths)){
				$("#age").val('');
				$("#datepicker").val('هذا التاريخ غير صالح');
				return;
			}
			if(diffMonths < 0){
				diffMonths+=12;
				diffYears--;
			}
			$("#age").val(diffYears+" سنه -"+diffMonths+" شهر -"+diffDays+" يوم ");
		});
	});

  function show_loading_screen(){
    $("#overlay").show();
  }
	</script>

	@yield('javascript')

	<!-- datepicker -->
	<script src="{{asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
  <!-- timepicker -->
	<script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
	<!-- AdminLTE App -->
	<script src="{{asset('dist/js/app.min.js')}}"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="{{asset('dist/js/demo.js')}}"></script>

</body>
</html>
