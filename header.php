    <meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1" name="viewport">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
</head>

<body class="bg-success">
<?php
$DB_HOST = "localhost";
$DB_USERNAME = "root"; 
$DB_PASSWORD = "mysql";
$DB_DATABASE = "slowpd"; 

$db = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
$db->set_charset("utf8");

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (isset($_SESSION['userid'])) {
?>
<nav id="top">| <a href="<?=$_SESSION['usertype']?>_cp.php">Control Panel</a> | <a href="logout.php">Log out</a> <?=$_SESSION['userid']?> |</nav>
<?php
}
?>