<?php
include 'session_include.php';
session_check('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create users</title>
<?php
include 'header.php';
?>
    <div class="container well" align="center">
       <h1>Create users</h1>
<?php
$userid = '';
$fname = '';
$lname = '';
$email = '';
$usertype = '';

if (isset($_POST['submit'])) {
    # using these variables to re-populate fields so that user doesn't have to re-enter every field if the form doesn't validate
    if (isset($_POST['userid'])) $userid = $_POST['userid'];
    if (isset($_POST['fname'])) $fname = $_POST['fname'];
    if (isset($_POST['lname'])) $lname = $_POST['lname'];
    if (isset($_POST['email'])) $email = $_POST['email'];
    if (isset($_POST['usertype'])) $usertype = $_POST['usertype'];
    
    if ($userid && $fname && $lname && $email && $usertype && isset($_POST['password1']) && isset($_POST['password2'])) {
        if ($_POST['password1'] == $_POST['password2']) {
            # check if user already registered
            $query = 'SELECT * FROM Users WHERE Id = ? OR Email = ?';
            $stmt = $db->prepare($query);
            $stmt->bind_param("ss", $userid, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            if ($result->num_rows == 0) {
                # encrypt the password for security
                $password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
                
                # creates 23 character hash. Second parameter is $more_entropy which must be true under CYGWIN.
                $confirmationHash = uniqid("", TRUE);
                
                $query = 'INSERT INTO Users VALUES (?, ?, ?, ?, ?, ?, ?);';
                $stmt = $db->prepare($query);
                $stmt->bind_param("sssssss", $userid, $fname, $lname, $email, $password, $usertype, $confirmationHash);
                $stmt->execute();
                
                if ($stmt->affected_rows == 1) {
                    echo "<p>User created successfully.</p>";
                }
                else {
                    echo "<p>User creation failed.</p>";
                }
                $stmt->close();
            }
            else {
                echo '<p class="red">A user with this user ID or email address already exists.</p>';
            }
        }
        else {
            echo '<p class="red">Passwords don\'t match.</p>';
        }
    }
    else {
        echo '<p class="red">Please fill in all fields.</p>';
    }
}
?>
   <form class="form-horizontal" method="post" action="create_users.php" style="max-width: 50%">
       <p class="red">User ID should be first part of email address if using an Algonquin email address</p>
	   	<div class="form-group">
            <label class="col-sm-4 control-label" for="userid">User ID:</label>
            <div class="col-sm-8"><input class="form-control" name="userid" value="<?=$userid?>" required></div>
       </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="fname">First name:</label>
            <div class="col-sm-8"><input class="form-control" name="fname" value="<?=$fname?>" required></div>
       </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="lname">Last name:</label>
            <div class="col-sm-8"><input class="form-control" name="lname" value="<?=$lname?>" required></div>
       </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="email">Email address:</label>
            <div class="col-sm-8"><input class="form-control" type="email" name="email" value="<?=$email?>" required></div>
       </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="usertype">User type:</label>
            <div class="col-sm-8">
               <select class="form-control" name="usertype" required>
                   <option value="">-- Select user type --</option>
                   <option value="student"<? if ($usertype == 'student') echo " selected"; ?>>student</option>
                   <option value="teacher"<? if ($usertype == 'teacher') echo " selected"; ?>>teacher</option>
                   <option value="admin"<? if ($usertype == 'admin') echo " selected"; ?>>admin</option>
               </select>
            </div>
       </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="password1">Password:</label>
            <div class="col-sm-8"><input class="form-control" type="password" name="password1" required></div>
       </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="password2">Re-type password:</label>
            <div class="col-sm-8"><input class="form-control" type="password" name="password2" required></div>
       </div> 
		 <br>
		 <input class="btn btn-success" type="submit" name="submit"> &nbsp; <input class="btn btn-success" type="reset">
   </form>
</div>
<?php
include 'footer.php';
?>
</body>
</html>