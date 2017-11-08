<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Pd Reg | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/AdminLTE.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Pd</b>Registration</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
	 <p class="login-box-msg">Fill in to reset Password</p>
	@if (count($errors) > 0)
	  <div class="alert alert-danger">
		<strong>Whoops!</strong>
		There were some problems with your input.<br><br>
		<ul>
		  @foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		  @endforeach
		</ul>
	  </div>
	@endif
	<form class="form-signin" action="{{url('password/email')}}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
		<div class="form-group has-feedback">
			<label for="inputEmail"  class="sr-only">Email address</label>
		
			<input type="email" name="email"  id="inputEmail" class="form-control" placeholder="Email address" value="{{old('email')}}" required autofocus>
        </div>
		<button class="btn btn-md btn-primary " type="submit">Send</button>
      </form>
	</div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
</body>
</html>