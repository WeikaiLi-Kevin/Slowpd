<?php
/*
admin_cp.php
Created by Harvey Patterson
Modified by Slowpd

This is the home page or portal for administrators. This page is reached automatically when an admin logs in. This page allows admins to search for users, whereafter they can confirm, modify, or delete the users returned by the search. Admins can also create users of any level by clicking the Create Users link.
*/

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
            echo "<table class=\"table table-bordered\">
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
                
                echo <<< END
            <tr>
                <td>{$row['Id']}</td>
                <td>{$row['FirstName']} {$row['LastName']}</td>
                <td>{$row['Email']}</td>
                <td>{$row['UserType']}</td>
                <td>$status</td>
                <td>
                    <form method="post" action="modify_user.php">
                        <input type="submit" name="submit" class="btn btn-primary" value="Modify">
                        <input type="hidden" name="userid" value="{$row['Id']}">
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
    
     <form class="form-horizontal" method="post" action="admin_cp.php" style="max-width: 50%">
      <div class="form-group">
           <label class="form-check-label"><input type="checkbox" class="form-check-input" name="students" id="students"<?php if (isset($_POST['students'])) echo ' checked'; ?>> Students</label> &nbsp;
           <label class="form-check-label" for="teachers"><input class="form-check-input" type="checkbox" name="teachers" id="teachers"<?php if (isset($_POST['teachers'])) echo ' checked'; ?>> Teachers</label> &nbsp;
           <label class="form-check-label" for="admins"><input class="form-check-input" type="checkbox" name="admins" id="admins"<?php if (isset($_POST['admins'])) echo ' checked'; ?>> Administrators</label>
         </div>
        <div class="form-group">
            <label class="col-sm-4 control-label" for="fname">First name:</label>
            <div class="col-sm-8"><input class="form-control" name="fname" id="fname" value="<?=$fname?>"></div>
         </div>
           <div class="form-group">
               <label class="col-sm-4 control-label" for="lname">Last name:</label>
               <div class="col-sm-8"><input class="form-control" name="lname" id="lname" value="<?=$lname?>"></div>
         </div>
           <div class="form-group">
               <label class="col-sm-4 control-label" for="email">Email address:</label>
               <div class="col-sm-8"><input class="form-control" name="email" id="email" type="email" value="<?=$email?>"></div>
         </div>
        <input class="btn btn-success" name="submit" type="submit"> &nbsp; <input class="btn btn-success" type="reset">
    </form>
    
    <h2 style="font-weight: bold ;color:#026342"> Create users</h2>
    <p><a href="create_users.php"><input type="button" class="btn btn-primary btncheck" value="Create users"></a></p>
</div>
<?php
include 'footer.php';
?>
</body>
</html>