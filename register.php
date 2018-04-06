<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User registration</title>
<?php
include 'header.php';
?>
<div class="container well" align="center">
<?php
$fname = '';
$lname = '';
$email = '';
$emaildomain = '';

if (isset($_GET['conf'])) {
    $query = "UPDATE Users SET ConfirmationHash = '' WHERE ConfirmationHash = ?;";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $_GET['conf']);
    $stmt->execute();
    
    if ($stmt->affected_rows == 0) {
        echo "<h1>Already registered</h1>
        
        <p>It looks like you're already registered. You can delete your confirmation email.</p>";
    }
    else {
        include 'header.php';
        echo "<h1>Registration successful</h1>
        
<p>Thank you for confirming your registration. You can delete your confirmation email.</p>\n";
    }
    $stmt->close();
    echo '<p><a href="index.php">Log in</a></p>';
    
    include 'footer.php';

    echo '</body>
</html>';

    exit(); # close page so user doesn't see registration form
}
else if (isset($_POST['submit'])) {
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
                # encrypt the password for security
                $password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
                
                # must create $email variable so we don't get "Cannot pass parameter 5 by reference" during bind_param
                $fullemail = "$email@$emaildomain";
                # algonquinlive.com = student, algonquincollege.com = teacher
                $usertype = $emaildomain == 'algonquinlive.com' ? 'student' : 'teacher';
                # creates 23 character hash. Second parameter is $more_entropy which must be true under CYGWIN.
                $confirmationHash = uniqid("", TRUE);

                $query = 'INSERT INTO Users VALUES (?, ?, ?, ?, ?, ?, ?)';
                $stmt = $db->prepare($query);
                $stmt->bind_param("sssssss", $email, $fname, $lname, $fullemail, $password, $usertype, $confirmationHash);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                
                # *** Change $WEB_HOST in db_vars.php to point to wherever this website is hosted from!!! ***
                $confUrl = "$WEB_HOST/register.php?conf=$confirmationHash";
                
                #send email
                $to = $fullemail;
                $subject = "Confirmation email for Algonquin Student-Teacher Appointment Scheduler";
                $message = '<html>';
                $message .= '<body>';
                $message .= '<p>Thank you for registering an account with the Algonquin Student-Teacher Appointment Scheduler</p>';
                $message .= "<p><a href=\"$confUrl\">Click here to confirm registration</a></p>";
                $message .= "<p>If the link above doesn't work, copy this address into your browser: $confUrl</p>";
                # change this to whatever message you want to send from Algonquin College
                $message .= "<p>Thank you, Team Slowpd.</p>";
                $message .= '</body>';
                $message .= '</html>';
                $headers = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
                $headers .= "From: $EMAIL_FROM";
                
                mail($to,$subject,$message,$headers);
                
                # print successful registration page, and then end script processing
                echo '<h1>Registration complete</h1>

<p>You have successfully registered. Go to <a href="index.php">login page</a>.</p>';

                include 'footer.php';

                echo '</body>
</html>';

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
   <h1>Student-Teacher Appointment Scheduler Registration</h1>

   <form method="post" action="register.php">
	   	<table>
			<tr><td style="font-weight: bold;">First name: </td><td><input class="form-control" name="fname" value="<?=$fname?>" required></td></tr>
			<tr><td style="font-weight: bold;">Last name: </td><td><input class="form-control" name="lname" value="<?=$lname?>" required></td></tr>
			<tr><td style="font-weight: bold;">Email address: </td><td><input class="form-control" name="email" maxlength="8"  value="<?=$email?>" required>@
       <select name="emaildomain" required>
		   <option value="">-- Select email domain --</option>
           <option value="algonquinlive.com"<? if ($emaildomain == 'algonquinlive.com') echo " selected"; ?>>algonquinlive.com (student)</option>
           <option value="algonquincollege.com"<? if ($emaildomain == 'algonquincollege.com') echo " selected"; ?>>algonquincollege.com (teacher)</option>
       </select></td></tr>
	   
			<tr><td style="font-weight: bold;">Password: </td><td><input class="form-control" type="password" name="password1" required></td></tr>
			<tr><td style="font-weight: bold;">Re-type password: </td><td><input class="form-control" type="password" name="password2" required></td></tr>
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