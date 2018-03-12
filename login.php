<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<meta content="" name="description">
	<meta content="" name="author">	
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
<link href="css/business-frontpage.css" rel="stylesheet">

	<title>Appointment Scheduler</title>
<script type="text/javascript">
    function checkUser() {
		var un = document.getElementById("username").value;
	if(un=="student"){
		window.location="studentportal.php";
	}
	//else if(un=="teacher"){
	//	window.location="teacherportal.php";
	//}
        
    };
</script>
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button class="navbar-toggle" data-target="#bs-example-navbar-collapse-1" data-toggle="collapse" type="button"><span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button> <a class="navbar-brand" id="rulelist">Teacher Scheduler</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li>
						<a id="standings">Search</a>
					</li>					
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="#" id="nhlsched">Logoff</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="container">
		<div class="span10 well">		
			<div align="center">
				<div class="container">
				<div class="row"><div class="span1"><h1>Login</h1></div></div>
				<div class="row">
				<form action="studentportal.php" method="post">
					<div class="span2">Username:</div>
					<div class="span2"><input type="text" name="username" id="username"></div>
				</div>
				<div class="row">
					<div class="span2">Password:</div>
					<div class="span2"><input type="password" name="password"></div>
				</div>
				<br>
				<button type="button" class="btn btn-primary" onClick="checkUser()">Login</button>
				</form>
				</div>
			</div>
		</div>
</body>
</html>
