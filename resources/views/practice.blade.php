<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">
</head>
<body>
	
	<form action="{{ route('practice.store') }}" method="POST" enctype="multipart/form-data">

		@csrf
			
		<input type="file" name="photo">

		<button type="submit" >Submit</button>

	</form>

</body>
</html>