<?php
include 'session_include.php';
session_check('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Portal</title>
<?php
include 'header.php';
?>
<div class="container well" align="center">
    <h1>Welcome, <?=$_SESSION['realname']?></h1>
    
    <h2 style="font-weight: bold ;color:#026342">Manage users</h2>
<?php
$fname = '';
$lname = '';
$email = '';

if (isset($_POST['submit'])) {
    # using these variables to re-populate fields so that user doesn't have to re-enter every field if they forget to pick a usertype or no search results
    if (isset($_POST['fname'])) $fname = $_POST['fname'];
    if (isset($_POST['lname'])) $lname = $_POST['lname'];
    if (isset($_POST['email'])) $email = $_POST['email'];
    
    if (isset($_POST['admins']) || isset($_POST['teachers']) || isset($_POST['students'])) {
        # set up types of users to return in search
        $usertypes = [];
        
        if (isset($_POST['admins'])) $usertypes[] = 'admin';
        if (isset($_POST['teachers'])) $usertypes[] = 'teacher';
        if (isset($_POST['students'])) $usertypes[] = 'student';

        $usertypes = implode("','", $usertypes); # comma-separated list of usertypes, with internal single quotes

        $query = "SELECT * FROM Users WHERE (FirstName = ? OR LastName = ? OR Email = ?) AND UserType IN ('$usertypes');";            
        $stmt = $db->prepare($query);
        $stmt->bind_param("sss", $fname, $lname, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows == 0)
            echo "<p>No users matched your search.</p>";
        else {
            echo "<table class=\"border\">
            <caption>Results</caption>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>User type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>\n";

            while ($row = $result->fetch_assoc()) {
                $status = $row['ConfirmationHash'] == '' ? 'Registered' : 'Pending';
                $userName = "{$row['FirstName']} {$row['LastName']}"; # for form submission to modify or delete user page
                
                echo <<< END
            <tr>
                <td>{$row['Id']}</td>
                <td>$userName</td>
                <td>{$row['Email']}</td>
                <td>{$row['UserType']}</td>
                <td>$status</td>
                <td>
                    <form method="post" action="modify_user.php">
                        <input type="submit" name="submit" value="Modify">
                        <input type="hidden" name="userid" value="{$row['Id']}">
                        <input type="hidden" name="username" value="$userName">
                        <input type="hidden" name="email" value="{$row['Email']}">
                    </form>
                </td>
            </tr>
END;
            }

            echo "</table>";
        }
    }
    else {
        echo '<p class="red">Please choose at least one type of user to search for.</p>';
    }
}
?>
   <p>Search for users by first name, last name, or email.</p>
    
     <form method="post" action="admin_cp.php">
       <label class="checkbox-inline" for="students"><input type="checkbox" name="students" id="students">Students</label>
       <label class="checkbox-inline" for="teachers"><input type="checkbox" name="teachers">Teachers</label>
       <label class="checkbox-inline" for="admins"><input type="checkbox" name="admins">Administrators</label>
       
        
        <table>
        <tr><td style="font-weight: bold ;">First name: </td><td><input class="form-control" name="fname" value="<?=$fname?>"></td></tr>
        <tr><td style="font-weight: bold ;">Last name: </td><td><input class="form-control" name="lname" value="<?=$lname?>"></td></tr>
        <tr><td style="font-weight: bold ;">Email address: </td><td><input class="form-control" name="email" type="email" value="<?=$email?>"></td></tr>
     </table>     
        <input class="btn btn-success" name="submit" type="submit"> <input class="btn btn-success" type="reset">
    </form>
    
    <h2 style="font-weight: bold ;color:#026342"> Create users</h2>
    <p><a style="font-weight: bold ;color:#026342" href="create_users.php">Create users</a></p>
</div>
<?php
include 'footer.php';
?>
</body>
</html>