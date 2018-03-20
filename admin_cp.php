<?php
include 'session_include.php';
session_check('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Portal</title>
<?php
include 'header.php';
?>
    <h1>Welcome, <?=$_SESSION['realname']?></h1>
    
    <p><a href="manage_users.php">Manage users</a></p>
    <p><a href="create_users.php">Create users</a></p>

<?php
include 'footer.php';
?>
</body>
</html>