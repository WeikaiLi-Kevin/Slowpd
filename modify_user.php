<?php
/*
modify_user.php
Created by Harvey Patterson
Modified by Slowpd

This page is reached by POST from admin_cp.php when an administrator searches for users and clicks the "Modify" button for a specific user in the search result. This page allows the admin to change the user's name, email address, and user type.
*/

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
<div class="container well" align="center">
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
        <input class="btn btn-success" type="submit" name="submit" value="Confirm user">
        <input type="hidden" name="userid" value="<?=$row['Id']?>">
         <input type="hidden" name="email" value="<?=$row['Email']?>">
    </form>
<?php
    }
?>

  <h2>User details</h2>

   <form class="form-horizontal" method="post" action="confirm_change.php" style="max-width: 50%">
      <div class="form-group">
          <label for="fname" class="col-sm-4 control-label">First name:</label>
          <div class="col-sm-8">
              <input class="form-control" type="text" name="fname" value="<?=$row['FirstName']?>" required>          
          </div>
       </div>
      <div class="form-group">
          <label for="lname" class="col-sm-4 control-label">Last name:</label>
          <div class="col-sm-8">
              <input class="form-control" type="text" name="lname" value="<?=$row['LastName']?>" required>          
          </div>
       </div>
       <div class="form-group">
          <label for="email" class="col-sm-4 control-label">Email address:</label>
          <span class="col-sm-8">
              <input class="form-control" type="text" name="email" value="<?=$row['Email']?>" required>          
          </span>
       </div>
         <div class="form-group">
              <label for="usertype" class="col-sm-4 control-label">User type:</label>
              <div class="col-sm-8">
                  <select class="form-control" name="usertype" required>        
                  <option value="">-- Select user type --</option>
                   <option value="student"<?php if ($row['UserType'] == 'student') echo " selected"; ?>>student</option>
                   <option value="teacher"<?php if ($row['UserType'] == 'teacher') echo " selected"; ?>>teacher</option>
                   <option value="admin"<?php if ($row['UserType'] == 'admin') echo " selected"; ?>>admin</option>
               </select>                  
            </div>
       </div>

       <input type="hidden" name="userid" value="<?=$row['Id']?>">
       <input class="btn btn-primary" type="submit" name="submit"> &nbsp; <input class="btn btn-primary" type="reset">
   </form>
   
   <h2>Delete user</h2>
   
   <form method="post" action="delete_user.php">
         <input class="btn btn-danger" type="submit" name="submit" value="Delete user">
         <input type="hidden" name="userid" value="<?=$row['Id']?>">
         <input type="hidden" name="email" value="<?=$row['Email']?>">
   </form>
</div>
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