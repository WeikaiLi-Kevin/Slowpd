<?php
/*
delete_user.php
Created by Harvey Patterson
Modified by Slowpd

This page is reached by POST from modify_user.php when an administrator deletes a user.
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
    <title>Delete user</title>
<?php
include 'header.php';
?>
<div class="container well" align="center">
    <h1>Delete user</h1>
<?php
$query = "DELETE FROM Users WHERE Id = ? AND Email = ?;";
$stmt = $db->prepare($query);
$stmt->bind_param("ss", $_POST['userid'], $_POST['email']);
$stmt->execute();

if ($stmt->affected_rows == 1)
    echo "User {$_POST['userid']} has been deleted.</p>\n";
else
    echo "Delete of user {$_POST['userid']} failed.</p>\n";
?>    
    <p><a href="admin_cp.php">Return to Admin Control Panel</a></p>
</div>
<?php
$stmt->close();

include 'footer.php';
?>
</body>
</html>