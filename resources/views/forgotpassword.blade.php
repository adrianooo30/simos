<!DOCTYPE html>
<html>
<head>
	<title>Forgot Password</title>

	<link rel="stylesheet" type="text/css" href="{{asset('css/modals.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/icons/css/icons.css')}}"/>
   
    <link rel="stylesheet" type="text/css" href="{{asset('/css/icons/fontawesome/css/all.min.css')}}"/>

	<link rel="stylesheet" type="text/css" href="{{ asset('/bootstrap/dist/css/bootstrap.css') }}">

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


		.loader {
		  border: 5px solid lightgrey;
		  border-radius: 50%;
		  border-top: 5px solid #3498db;
		  width: 18px;
		  height: 18px;
		  -webkit-animation: spin 2s linear infinite; /* Safari */
		  animation: spin 2s linear infinite;
		}

		/* Safari */
		@-webkit-keyframes spin {
		  0% { -webkit-transform: rotate(0deg); }
		  100% { -webkit-transform: rotate(360deg); }
		}

		@keyframes spin {
		  0% { transform: rotate(0deg); }
		  100% { transform: rotate(360deg); }
		}

	</style>

</head>
<body>
	<div class="container">
		
		<input type="hidden" id="page" value="forgotpassword">
		 
		
		

		<form method="GET" onsubmit="return checkUser()" id="--forgot-password" style="display: block;">
			@csrf

			<div class="log-in-container">

				<div class="card login-card">

					<div class="card-header text-center">
						<h5 class="text-primary">Forgot Password</h5>

					</div>
					
					<div class="card-body text-center">
						<div class="form-group">
							<label class="text-primary imp">
								<i class="ti-user"></i>
								Username
							</label>
							<div class="input-field">
								<input id="username-forgotpassword" type="text" name="username" class="form-control for-login" placeholder="Username" required>
								{{-- <label id="error-user" ></label> --}}
								<p id="error-user" style="color:red"></p>

								
							</div>
						</div>
						
						<br>
						<div>
							<button type="submit" id="rec_btn" class="btn btn-primary">
								Request Recovery Code
							</button>

							<a id="back_btn" href="{{ route('logout') }}" class="btn btn-danger">
								Back
							</a>
							
						</div>
					</div>
					
					<div  id="loading" style="display:none; color:dodgerblue; font-size: 15px; margin-bottom: 10px">
						<div class="loader" style="text-align: center; margin-left: auto; margin-right: auto"></div>
						<div style="text-align: center;"><b>Generating Recovery Code, Please Wait.....</b></div>
					</div>

					
					
				</div>
			</div>
		</form>


		<form action="" method="GET" onsubmit="return checkRecoveryCode()" id="--forgot-password-recovery" style="display: none">
			@csrf
			<div class="log-in-container">
				<div class="card login-card">
					<div class="card-header text-center">
						<h5 class="text-primary">Forgot Password</h5>

					</div>
					
					<div class="card-body text-center">
						<div class="form-group">
							<label class="text-primary imp">
								<i class="ti-user"></i>
								Username
							</label>
							<div class="input-field">
								<input id="username-forgotpassword1" type="text" name="username" class="form-control for-login" disabled>
								{{-- <label id="error-user" ></label> --}}
								<p id="error-user" style="color:red"></p>
							</div>

							<label class="text-primary imp">
								Recovery Code
							</label>
							<div class="input-field">
								<input id="recovery-code" type="text" name="username" class="form-control for-login" placeholder="Recovery Code" required>
								{{-- <label id="error-user" ></label> --}}
								<p id="error-code" style="color:red"></p>
								<p id="attempts" style="color:red"></p>

								
							</div>
						</div>
						
						<br>
						<div>
							<button id="btn_proceed" type="submit" class="btn btn-primary">
								Proceed
							</button>

							<button  onclick="closeForgotPassword1()" class="btn btn-danger">
								Back
							</button>
							
						</div>
					</div>
					
				</div>
			</div>
		</form>


		<form action="{{url('changePassword')}}" method="POST" onsubmit="return changePassword()" id="change-password" style="display: none">
			@csrf
			<div class="log-in-container">
				<div class="card login-card">
					<div class="card-header text-center">
						<h5 class="text-primary">Change Password</h5>

					</div>
					
					<div class="card-body text-center">
						<div class="form-group">
							<label class="text-primary imp">
								<i class="ti-key"></i>
								New Password
							</label>
							<div class="input-field">
								<input id="newpassword" type="password" name="newpassword" placeholder="Enter New Password" class="form-control for-login" required>
							</div>


							<label class="text-primary imp" style="margin-top:8px">
								<i class="ti-key"></i>
								Confirm Password
							</label>
							<div class="input-field">
								<input id="confirmpassword" type="password" name="confirmpassword" placeholder="Confirm New Password" class="form-control for-login" required>
							</div>
							<br>
							<p id="message" style="color:red"></p>

							
						</div>
						
						<br>
						<div>
							<button type="submit" class="btn btn-primary">
								Proceed
							</button>

							<a  href="{{ route('logout') }}" class="btn btn-danger">
								Cancel
							</a>
							
						</div>
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

	<script src="{{ asset('/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>

	    <script type="text/javascript">

    	var attempts = 5;
    	
    	// function forgotPassword(){

    	// 	document.getElementById('--form-login').style.display = 'none';
    	// 	document.getElementById('--forgot-password').style.display = 'block';

    	// 	document.getElementById('username-forgotpassword').value = document.getElementById('username').value;
    		

    		
    	// }

    	// function closeForgotPassword(){
    		
    	// 	document.getElementById('--forgot-password').style.display = 'none';
    	// 	document.getElementById('--form-login').style.display = 'block';

    	// 	document.getElementById('username-forgotpassword').value = '';
    	// }

    	$('#--forgot-password').on('submit', function( e ){
    		e.preventDefault();

    		console.log( e );
    	});

    	function closeForgotPassword1(){
    		

    		document.getElementById('--forgot-password-recovery').style.display = 'none';
    		document.getElementById('--forgot-password').style.display = 'block';

    		document.getElementById('username-forgotpassword').disabled = false;
    		document.getElementById('rec_btn').disabled = false;
    		
    	}


    	function checkUser(){
    		
    		document.getElementById('loading').style.display = 'block';

    		var username = document.getElementById('username-forgotpassword').value;

    		document.getElementById('error-user').innerHTML = "";

    		document.getElementById('username-forgotpassword').disabled = true;
    		document.getElementById('rec_btn').disabled = true;


    		//reset
    		document.getElementById('error-code').innerHTML = "";
    		document.getElementById('attempts').innerHTML =  "";
			document.getElementById('btn_proceed').disabled = false;
			document.getElementById('recovery-code').value = "";
			attempts = 5; 



    		let xmlrequest = new XMLHttpRequest();
			xmlrequest.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){

					document.getElementById('loading').style.display = 'none';



					let json = JSON.parse(this.responseText);   

					// alert(json);

					if(json == "does not exist"){

						document.getElementById('error-user').innerHTML = "*** Username does not exist! ***";

						document.getElementById('username-forgotpassword').disabled = false;
    					document.getElementById('rec_btn').disabled = false;
					}

					else{
						document.getElementById('error-user').innerHTML = "";

						document.getElementById('--forgot-password').style.display = 'none';
    					document.getElementById('--forgot-password-recovery').style.display = 'block';

    					document.getElementById('username-forgotpassword1').value = document.getElementById('username-forgotpassword').value;

					}

					

				}	

			}
			
			xmlrequest.open('GET', '/forgotPasswordCheckUser/'+username, true);
			xmlrequest.send();

			return false;
    	}


    	function checkRecoveryCode(){
    		
    		var recoverycode = document.getElementById('recovery-code').value;


    		// alert(recoverycode);

    		

    		let xmlrequest = new XMLHttpRequest();
			xmlrequest.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){

					let json = JSON.parse(this.responseText);   

				

					if(json == "correct"){
						
						// document.getElementById('error-code').innerHTML = "";

						document.getElementById('--forgot-password-recovery').style.display = 'none';
						document.getElementById('change-password').style.display = 'block';
						
					}

					else{

						attempts--;

						if(attempts != 0){
							document.getElementById('error-code').innerHTML = "*** Wrong Recovery Code! ***";

							if(attempts == 1){
								document.getElementById('attempts').innerHTML =  "*** " + attempts + " Attempt Remaining! ***";
							}

							else{
								document.getElementById('attempts').innerHTML =  "*** " + attempts + " Attempts Remaining! ***";
							}
							
						}


						else{
							document.getElementById('error-code').innerHTML = "*** Wrong Recovery Code! ***";
							document.getElementById('attempts').innerHTML =  "*** Recovery Code Expired! ***";
							document.getElementById('btn_proceed').disabled = true;
						}
						
					}

				}	

			}
			
			xmlrequest.open('GET', '/forgotPasswordCheckRecoveryCode/'+recoverycode, false);
			xmlrequest.send();


			return false;
    	}


    	function changePassword(){
    		
    		var newpassword = document.getElementById('newpassword').value;
    		var confirmpassword = document.getElementById('confirmpassword').value;


    		var x = true;

    		if(newpassword.length < 5){
    			document.getElementById('message').innerHTML = '*** Password must be at least 5 characters ***';

    			x = false;
    		}


    		else{
    			
    			if(newpassword != confirmpassword){
	    			document.getElementById('message').innerHTML = '*** Password does not match ***';

	    			x = false;
    			}
    		}

			return x;
    	}



    </script>

</body>
</html>