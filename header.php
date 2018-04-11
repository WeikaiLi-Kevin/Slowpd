<?php
/*
header.php
Created by Harvey Patterson
Modified by Dave Sampson

This page is "include"d on every page, immediately before the </head> tag, which should no longer written. It includes the meta and style information common to every page. It performs important tasks like opening the database connection, and setting the timezone.

Counterintuitively, it also includes a partial copy of footer.php in a function called endpage(). This is because it is sometimes desirable to stop rendering a page early -- such as to report an error -- without rendering the normal contents of the page. In this case, we still want to produce a page that contains valid HTML (must close the div, body, and html tags). "Include"-ing footer.php often throws errors because the database connection may already be null. Calling endpage() creates the possibility that an open database connection will not be closed. This is okay! PHP recommends always closing a db connection manually to free resources earlier, but it will be closed automatically eventually.
*/
?>
    <meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1" name="viewport">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./css/ourstyle.css">

</head>

<body class="bg-success">
<?php
/*
Function endpage()
By Harvey Patterson

This function can be called when you want to stop executing PHP on a page and close the HTML tags. Probably only useful for displaying an error message and then preventing normal page content from loading.
*/
function endpage() {
    echo '</div>
    <footer>&copy; 2018 Slowpd. All rights reserved.</footer>
</body>
</html>';
    exit();
}

/*
Very important. Do not touch! This is how PHP correctly converts dates from "seconds since 1970-01-01" (Unix epoch) into the equivalent time in Ottawa, Canada. If this is not done, PHP may assume the timezone to be GMT, which will make appointments unbookable 4/5 hours too early (depending on whether it is currently Standard or Daylight Saving Time). Can also be set in php.ini, but it's declared here to be safe.
*/
date_default_timezone_set('America/Toronto');
include 'db_vars.php';

$db = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
$db->set_charset("utf8");

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
	<nav class="navbar navbar-inverse navbar-fixed-top navbar-default" role="navigation">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<a href="#" class="navbar-left"><img class="img-rounded" src="./img/gonq.png"></a>
			</div><!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li>
						<a class="nav-link">Algonquin College Student-Teacher Appointment Scheduler</a>
					</li>
<?php
if (isset($_SESSION['userid'])) {
?>
					<li>
						<a class="nav-link"><?=$_SESSION['realname']?></a>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a class="nav-link" href="<?=$_SESSION['usertype']?>_cp.php">Control Panel</a>
					</li>
					<li>
						<a href="logout.php" class="nav-link">Log out</a>
					</li>
<?php
}
else {
?>
				</ul>
			</div>
<?php    
}
?>            
		</div>
	</nav>