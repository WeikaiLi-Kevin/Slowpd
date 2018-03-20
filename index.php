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

$username = '';

if (isset($_POST['submit'])) {
    # using this variables to re-populate username so that can see what they entered if username/password pair doesn't validate
    if (isset($_POST['username'])) $username = $_POST['username'];
    
    if ($username && isset($_POST['password'])) {
        $query = 'SELECT * FROM Users WHERE id = ? AND password = ?';
        $stmt = $db->prepare($query);
        $stmt->bind_param("ss", $username, $_POST['password']);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $_SESSION['usertype'] = $row['UserType'];
            $_SESSION['userid'] = $row['Id'];
            $_SESSION['realname'] = "${row['FirstName']} {$row['LastName']}";
            redirect();
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
   <h1>Algonquin College Student-Teacher Appointment Scheduler</h1>
   
    <form method="post" action="index.php">
        Username: <input name="username" value="<?=$username?>" required> (the first part of your Algonquin email address)<br>
        Password: <input name="password" type="password" required><br>
        <input name="submit" type="submit" value="Login"> <input type="reset">
    </form>
    
    <p>Not registered? Register <a href="register.php">here</a>.</p>

<?php
include 'footer.php';
?>
</body>
</html>