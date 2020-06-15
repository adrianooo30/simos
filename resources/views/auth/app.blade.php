<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    
    {{-- style dot css --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/mainStyle.css' )}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css') }}">

    <link rel="icon" href="{{ asset('/images/logos/logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style type="text/css">

        body{
            background: url(/images/backgrounds/background.jpg), rgba(0,0,0,0.2);
            background-size: cover;
            background-repeat: no-repeat;
            background-blend-mode: multiply;
        }

    </style>
</head>
<body>
    
	@yield('content')

<div class="container-fluid text-center">
    <h6 class="lighter text-muted">Copyright &copy;2019 Vicious</h6>
</div>

{{-- default scripts --}}
<script src="{{ asset('/js/app.js') }}"></script>
<script src="{{ asset('/js/mainScript.js') }}"></script>


</body>
</html>


 {{-- <div class="card-footer text-center pr-4 pl-4">
                        <h6 class="text-muted py-3">
                            <a href="{{ url('viewForgotPassword') }}">Forgot Password?</a>
                        </h6>
                    </div> --}}