<?php
include 'session_include.php';
session_check('admin');

if ($_POST == [])   # page wasn't reached by POST from admin_cp.php
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
$query = "SELECT * FROM Users WHERE Id = ? AND Email = ?;";
$stmt = $db->prepare($query);
$stmt->bind_param("ss", $_POST['userid'], $_POST['email']);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    
    if ($row['ConfirmationHash'] != '') {
?>
 <h2>User is not confirmed</h2>
 
    <form method="post" action="confirm_user.php">
        <input type="submit" name="submit" value="Confirm user">
        <input type="hidden" name="userid" value="<?=$row['Id']?>">
         <input type="hidden" name="email" value="<?=$row['Email']?>">
    </form>
<?php
    }
?>

  <h2>User details</h2>

   <form method="post" action="confirm_change.php">
       First name: <input name="fname" value="<?=$row['FirstName']?>" required><br>
       Last name: <input name="lname" value="<?=$row['LastName']?>" required><br>
       Email address: <input name="email" type="email" size="20" value="<?=$row['Email']?>" required><br>
       User type: <select name="usertype" required>
          <option value="">-- Select user type --</option>
           <option value="student"<? if ($row['UserType'] == 'student') echo " selected"; ?>>student</option>
           <option value="teacher"<? if ($row['UserType'] == 'teacher') echo " selected"; ?>>teacher</option>
           <option value="admin"<? if ($row['UserType'] == 'admin') echo " selected"; ?>>admin</option>
       </select><br>
       <input type="hidden" name="userid" value="<?=$row['Id']?>">
       <input type="submit" name="submit"> <input type="reset">
   </form>
   
   <h2>Delete user</h2>
   
   <form method="post" action="delete_user.php">
         <input type="submit" name="submit" value="Delete user">
         <input type="hidden" name="userid" value="<?=$row['Id']?>">
         <input type="hidden" name="email" value="<?=$row['Email']?>">
   </form>
<?php
}
else {
    echo '<p>Something went wrong.</p>
    
    <p>Return to <a href="admin_cp.php">Control Panel</a>.</p>';
}

include 'footer.php';
?>
</body>
</html>