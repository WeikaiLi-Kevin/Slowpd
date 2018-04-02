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
include 'db_vars.php';

$db = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
$db->set_charset("utf8");

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (isset($_SESSION['userid'])) {
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
				</ul>
			</div>
		</div>
	</nav>
<?php
}
?>
