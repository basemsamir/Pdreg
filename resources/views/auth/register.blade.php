@extends('..\app')

@section('content')   
	<form class="form-signin" action="{{url('auth/register')}}" method="post">
        <h2 class="form-signin-heading">Register</h2>
		<label for="inputUsername" class="sr-only">Username</label>
        <input type="text" id="inputUsername" name="name" class="form-control" placeholder="Username" required autofocus>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
		<label for="inputConfirmPassword" class="sr-only">Confirm Password</label>
        <input type="password" id="inputConfirmPassword" class="form-control" placeholder="ConfirmPassword" required>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button class="btn btn-md btn-primary " type="submit">Register</button>
      </form>
@endsection