<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User registration</title>
<?php
include 'header.php';

$fname = '';
$lname = '';
$email = '';
$emaildomain = '';

if (isset($_POST['submit'])) {
    # using these variables to re-populate fields so that user doesn't have to re-enter every field if the form doesn't validate
    if (isset($_POST['fname'])) $fname = $_POST['fname'];
    if (isset($_POST['lname'])) $lname = $_POST['lname'];
    if (isset($_POST['email'])) $email = $_POST['email'];
    if (isset($_POST['emaildomain'])) $emaildomain = $_POST['emaildomain'];

    
    if ($fname && $lname && $email && $emaildomain && isset($_POST['password1']) && isset($_POST['password2'])) {
        if ($_POST['password1'] == $_POST['password2']) {
            # check if user already registered
            $query = 'SELECT * FROM Users WHERE Id = ?';
            $stmt = $db->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            if ($result->num_rows == 0) {
                # must create $email variable so we don't get "Cannot pass parameter 5 by reference" during bind_param
                $fullemail = "$email@$emaildomain";
                # algonquinlive.com = student, algonquincollege.com = teacher
                $usertype = $emaildomain == 'algonquinlive.com' ? 'student' : 'teacher';
                # creates 23 character hash. Second parameter is $more_entropy which must be true under CYGWIN.
                $confirmationHash = uniqid("", TRUE);

                $query = 'INSERT INTO Users VALUES (?, ?, ?, ?, ?, ?, ?)';
                $stmt = $db->prepare($query);
                $stmt->bind_param("sssssss", $email, $fname, $lname, $fullemail, $_POST['password1'], $usertype, $confirmationHash);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                
                # print successful registration page, and then end script processing
?>
<h1>Registration complete</h1>

<p>You have successfully registered. Go to <a href="index.php">login page</a>.</p>
<?php
                include 'footer.php';
?>
    </body>
</html>
<?php
                exit(); # close page so user doesn't see registration form
            }
            else {
                echo '<p class="red">You are already registered. If you have not yet confirmed your registration, please check your email for your confirmation link.</p>';
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
<div class="container well" align="center">
   <h1>Student-Teacher Appointment Scheduler Registration</h1>

   <form  method="post" action="register.php">

       
	   
	   
	   
	   	<table>
			<tr><td style="font-weight: bold ;">First name: </td><td><input class="form-control" name="fname" value="<?=$fname?>" required></td></tr>
			<tr><td style="font-weight: bold ;">Last name: </td><td><input class="form-control" name="lname" value="<?=$lname?>" required></td></tr>
			<tr><td style="font-weight: bold ;">Email address: </td><td><input class="form-control" name="email" maxlength="8"  value="<?=$email?>" required>@
       <select name="emaildomain" required>
		   <option value="">-- Select email domain --</option>
           <option value="algonquinlive.com"<? if ($emaildomain == 'algonquinlive.com') echo " selected"; ?>>algonquinlive.com (student)</option>
           <option value="algonquincollege.com"<? if ($emaildomain == 'algonquincollege.com') echo " selected"; ?>>algonquincollege.com (teacher)</option>
       </select></td></tr>
	   
			<tr><td style="font-weight: bold ;">Password: </td><td><input class="form-control" type="password" name="password1" required></td></tr>
			<tr><td style="font-weight: bold ;">Re-type password: </td><td><input class="form-control" type="password" name="password2" required></td></tr>
		 </table> 
		 <br>
		 <input class="btn btn-success" type="submit" name="submit"> <input class="btn btn-success" type="reset">
   </form>
  
	<br>
   <p>Already registered? Return to <a href="index.php">login page</a>.</p>
</div>
<?php
include 'footer.php';
?>
</body>
</html>