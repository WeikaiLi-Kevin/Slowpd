<?php
/*
confirm_user.php
Created by Jie Wang
Modified by Slowpd

This page is reached by POST from modify_user.php when an administrator confirms a user who has registered but whose account is still locked. Most likely, the user has lost or did not receive the confirmation email (always check your spam folder).
*/

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
<div class="container well" align="center">
    <h1>Confirm user</h1>
<?php
$query = "UPDATE Users SET confirmationHash = '' WHERE Id = ? AND Email = ?;";
$stmt = $db->prepare($query);
$stmt->bind_param("ss", $_POST['userid'], $_POST['email']);
$stmt->execute();

if ($stmt->affected_rows == 1)
    echo "<p>User {$_POST['userid']} confirmed.</p>";
else
    echo "<p>Confirmation of user {$_POST['userid']} failed.</p>";
?>
    <p>Return to <a href="admin_cp.php">Control Panel</a>.</p>
</div>
<?php
$stmt->close();

include 'footer.php';
?>
</body>
</html>