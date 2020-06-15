<!DOCTYPE html>
<html>
<head>
	<title>Login to SIMOS</title>

	<link rel="stylesheet" type="text/css" href="{{asset('css/modals.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/icons/css/icons.css')}}"/>
   
    <link rel="stylesheet" type="text/css" href="{{asset('/css/icons/fontawesome/css/all.min.css')}}"/>

	<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap/dist/css/bootstrap.css') }}">
	
	{{-- custom css --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/css/customs/custom-style.css') }}">

	<link rel="icon" href="{{ asset('/images/logos/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<style type="text/css">
		body{
			background: url(/images/backgrounds/background.jpg), rgba(0,0,0,0.2);
			background-size: cover;
			background-repeat: no-repeat;
			background-blend-mode: multiply;
		}
		.log-in-container{
			display: flex;
			height: 90vh;
			width: 100%;
		}
		.card{
			margin: auto;
			min-height: 300px;
			width: 400px;
			align-items: stretch;
		}
		input{
			text-align: center;
		}
		input.form-control{
			width:calc(100% - 1.5rem);
		}
		input::placeholder{
			text-align: center;
		}

	</style>
</head>
<body>
	<div class="container">
		<form action="{{ route('login.store') }}" method="POST" id="--form-login">
			@csrf
			<div class="log-in-container">
				<div class="card login-card">
					<div class="card-header text-center">
						<h5 class="text-primary">Login to SIMOS</h5>
					</div>
					<div class="card-body text-center">
						<div class="form-group">
							<label class="text-primary imp">
								<i class="ti-user"></i>
								Username
							</label>
							<div class="input-field">
								<input type="text" name="username" class="form-control for-login" placeholder="Username" value="{{ $username ?? old('username') }}" autocomplete="off">
								@error('username')
									<label class="error-message for-login my-2 imp">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<div class="form-group">
							<label class="text-primary imp">
								<i class="ti-key"></i>
								Password
							</label>
							<div class="input-field">
								<input type="password" name="password" class="form-control for-login" placeholder="Password" value="{{ $password ?? old('password') }}" autocomplete="off">
								@error('password')
									<label class="error-message for-login my-2 imp">{{ $message }}</label>
								@enderror
							</div>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary form-control">
								Login
							</button>
							@if(session()->has('errorMessage'))
								<div class="alert alert-danger my-3">
									<strong>
										<i class="fas fa-exclamation-triangle text-danger"></i> Error.
									</strong>
									{{ session()->get('errorMessage') }}
								</div>
							@endif
						</div>
					</div>
					<div class="card-footer text-center pr-4 pl-4">
						<h6 class="text-muted py-3">
							<a href="{{ url('viewForgotPassword') }}">Forgot Password?</a>
						</h6>
					</div>
				</div>
			</div>
		</form>
		<div class="container-fluid text-center">
			<h6 class="lighter text-muted">Copyright &copy;2019 Vicious</h6>
		</div>
	</div>
	

	{{--  --}}
	<script src="{{asset('/js/jquery-3.4.1.min.js')}}"></script>
	<script src="{{asset('/js/axios.min.js')}}"></script>
	
	{{-- sweet alert plugin --}}
	<script src="{{asset('/plugins/sweetalert/dist/sweetalert.min.js')}}"></script>

	<script src="{{ asset('/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>

	{{-- custom javascript --}}
	<script src="{{asset('/js/scripts.js')}}" type="text/javascript"></script>

	{{-- scripts --}}
	{{-- <script src="{{ asset('/js/login.js') }}"></script> --}}

</body>
</html>