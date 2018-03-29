<?php
include 'session_include.php';
session_check('admin');

if ($_POST == [])   # page wasn't reached by POST from modify_user.php
    header('Location:admin_cp.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm user</title>
<?php
include 'header.php';
?>
    <h1>Confirm user</h1>
<?php
$query = "UPDATE Users SET confirmationHash = '' WHERE Id = ?;";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $_POST['userid']);
$stmt->execute();

if ($stmt->affected_rows == 1)
    echo "<p>User {$_POST['userid']} confirmed.</p>";
else
    echo "<p>Confirmation of user {$_POST['userid']} failed.</p>";
?>
    <p>Return to <a href="manage_users.php">Manage Users</a>.</p>
<?
$stmt->close();

include 'footer.php';
?>
</body>
</html>