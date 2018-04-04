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
                # creates 23 character hash. Second parameter is $more_entropy which must be true under CYGWIN.
                $confirmationHash = uniqid("", TRUE);
                
                $query = 'INSERT INTO Users VALUES (?, ?, ?, ?, ?, ?, ?);';
                $stmt = $db->prepare($query);
                $stmt->bind_param("sssssss", $userid, $fname, $lname, $email, $_POST['password1'], $usertype, $confirmationHash);
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
   <form method="post" action="create_users.php">
	   	<table>
	   	    <tr>
	   	        <td colspan="2" class="red">User ID should be first part of email address if using an Algonquin email address</td>
	   	    </tr>
			<tr>
			    <td style="font-weight: bold;">User ID:</td>
			    <td><input class="form-control" name="userid" value="<?=$userid?>" required></td>
			</tr>
			<tr>
			    <td style="font-weight: bold;">First name:</td>
			    <td><input class="form-control" name="fname" value="<?=$fname?>" required></td>
			</tr>
			<tr>
              <td style="font-weight: bold;">Last name:</td>
               <td><input class="form-control" name="lname" value="<?=$lname?>" required></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Email address:</td>
                <td><input class="form-control" type="email" name="email"  value="<?=$email?>" required></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">User type:</td>
                <td>
                   <select name="usertype" required>
                       <option value="">-- Select user type --</option>
                       <option value="student"<? if ($usertype == 'student') echo " selected"; ?>>student</option>
                       <option value="teacher"<? if ($usertype == 'teacher') echo " selected"; ?>>teacher</option>
                       <option value="admin"<? if ($usertype == 'admin') echo " selected"; ?>>admin</option>
                   </select>
               </td>
           </tr>
			<tr>
			    <td style="font-weight: bold;">Password:</td>
			    <td><input class="form-control" type="password" name="password1" required></td>
			 </tr>
			<tr>
			    <td style="font-weight: bold;">Re-type password:</td>
			    <td><input class="form-control" type="password" name="password2" required></td>
			 </tr>
		 </table> 
		 <br>
		 <input class="btn btn-success" type="submit" name="submit"> <input class="btn btn-success" type="reset">
   </form>
</div>
<?php
include 'footer.php';
?>
</body>
</html>