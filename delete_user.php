<?php
include 'session_include.php';
session_check('admin');

if ($_POST == [])   # page wasn't reached by POST from manage_users.php
    header('Location:admin_cp.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete user</title>
<?php
include 'header.php';

$query = "DELETE FROM Users WHERE Id = ? AND Email = ?;";
$stmt = $db->prepare($query);
$stmt->bind_param("ss", $_POST['userid'], $_POST['email']);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>
    <h1>Delete user</h1>
    
    <p><?=$_POST['userid']?> (<?=$_POST['username']?>) has been deleted.</p>
    
    <p><a href="admin_cp.php">Return to Admin Control Panel</a></p>
<?php
include 'footer.php';
?>
</body>
</html>