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
<link href="css/business-frontpage.css" rel="stylesheet">
<title>Appointment Scheduler</title>
<script type="text/javascript">
<?php
$myappt = $_POST["appt"];
$mycourse = $_POST["course"];
$mynotes = $_POST["notes"];
$arr1 = str_split($myappt);
echo $myappt;
$day=$arr1[0];
$day.=$arr1[1];
$day.=$arr1[2];
echo $day;

switch ($day) {
    case "mon":
        $day="monday!!!";
        break;
    case "tue":
        $day="tuesday";
        break;
    case "wed":
        $day="wednesday";
        break;
    case "thu":
        $day="thursday";
        break;
    case "fri":
        $day="friday";
        break;		
}
echo $day;

$week = file_get_contents('week.json');
$data = json_decode($week, true);
$data[$day][$myappt] = "Unavailable";
$data = json_decode($week, true);
$newJsonString = json_encode($data);
file_put_contents('week.json', $newJsonString);

$week = file_get_contents('week.json');
$data = json_decode($week, true);
//$data[$day][$myappt][] = array('course'=>$mycourse, 'notes'=>$mynotes, 'confirmed'=>'no');
//$data[$day][$myappt][details] = (object)['course'=>$mycourse, 'notes'=>$mynotes, 'confirmed'=>'no'];
$data[$day][$myappt]["course"] = $mycourse;
$data[$day][$myappt]["notes"] = $mynotes;
$data[$day][$myappt]["confirmed"] = "no";
$data[$day][$myappt]["status"] = "pending";
$data[$day][$myappt]["student"] = "student";
//var details = {course:$mycourse, notes:$mynotes, confirmed:'no'};
$newJsonString = json_encode($data);
file_put_contents('week.json', $newJsonString);


?>
	
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
				<div class="row"><div class="span1"><h1>Appointment Requested</h1></div></div>
				<p>The teacher has been notified of your request. Appointments are confirmed once you receive a confirmation email from the professor.</p>
				<p><strong>Appointment: </strong><?php echo $_POST["appt"] ?></p>
				<p><strong>Teacher: </strong><?php echo $_POST["professor"] ?></p>
				<p><strong>Course: </strong><?php echo $_POST["course"] ?></p>
				<p><strong>Notes: </strong><?php echo $_POST["notes"] ?></p>
				<br>
				<button type="button" class="btn btn-primary" onClick="location.href='login.php';">OK</button>

			</div>
		</div>
</body>
</html>