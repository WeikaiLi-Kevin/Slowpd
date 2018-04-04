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
    <title>Modify user</title>
<?php
include 'header.php';
?>
    <h1>Modify user</h1>
<?php
$query = "UPDATE Users SET FirstName = ?, LastName = ?, Email = ?, UserType = ? WHERE Id = ?;";
$stmt = $db->prepare($query);
$stmt->bind_param("sssss", $_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['usertype'], $_POST['userid']);
$stmt->execute();

if ($stmt->affected_rows == 1)
    echo "<p>User {$_POST['userid']} modified.</p>";
else
    echo "<p>Modification of user {$_POST['userid']} failed.</p>";
?>
    <p>Return to <a href="admin_cp.php">Control Panel</a>.</p>
<?
$stmt->close();

include 'footer.php';
?>
</body>
</html>