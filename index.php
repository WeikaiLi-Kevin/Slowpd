<?php
# do not use include 'session_include.php' or it will redirect in an infinte loop!
session_start();

function redirect(){
    if (isset($_SESSION['usertype'])) {
        if ($_SESSION['usertype'] == 'admin') {
            header('Location:admin_cp.php');
        }
        else if ($_SESSION['usertype'] == 'teacher') {
            header('Location:teacher_cp.php');
        }
        else if ($_SESSION['usertype'] == 'student') {
            header('Location:student_cp.php');
        }   
    }
}

redirect(); # No need to login if user is already logged in!
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
<?php
include 'header.php';
?>
<div class="container well" align="center">
   <h1 style="font-family: verdana">Welcome</h1>
   
   <p>The Algonquin College Student-Teacher Appointment scheduler allows students to book appointments with teachers who have office hours on campus.</p>
<?php
$username = '';

if (isset($_POST['submit'])) {
    # using this variables to re-populate username so that can see what they entered if username/password pair doesn't validate
    if (isset($_POST['username'])) $username = $_POST['username'];
    
    if ($username && isset($_POST['password'])) {
        $query = 'SELECT * FROM Users WHERE id = ?';
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            if (password_verify($_POST['password'], $row['Password'])) {
                if ($row['ConfirmationHash'] == '') {
                    $_SESSION['usertype'] = $row['UserType'];
                    $_SESSION['userid'] = $row['Id'];
                    $_SESSION['realname'] = "${row['FirstName']} {$row['LastName']}";
                    redirect();                
                }                
                else {
                    echo '<p class="red">You have not confirmed your registration. Please check your email for your confirmation code.</p>';
                }
            }
            else {
                echo '<p class="red">Invalid id or password.</p>';
            }
        }
        else {
            echo '<p class="red">Invalid id or password.</p>';
        }
    }
    else {
        echo '<p class="red">Please fill in all fields.</p>';
    }
}
?>
   <br>
    <form class="form-horizontal" method="post" action="index.php" style="max-width: 50%">
        <div class="form-group">
            <label class="col-sm-4 control-label" for="username">Username:</label>
            <div class="col-sm-8"><input name="username" class="form-control" value="<?=$username?>" required></div>
        </div>
		<div class="form-group">
		    <label class="col-sm-4 control-label" for="password">Password:</label>
            <div class="col-sm-8"><input name="password" class="form-control" type="password" required></div>
        </div>
	<input name="submit" class="btn btn-success" type="submit" value="Login"> &nbsp; <input class="btn btn-success" type="reset">
    </form>
    <br>
    <p>Not registered? Register <a href="register.php">here</a>.</p>
	
	
	
</div>
<?php
include 'footer.php';
?>
</body>
</html>