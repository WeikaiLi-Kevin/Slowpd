<?php
include 'session_include.php';
session_check('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage users</title>
<?php
include 'header.php';
?>
    <h1>Manage users</h1>
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
                    <form method="post" action="delete_user.php">
                        <input type="submit" name="submit" value="Delete">
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
    
     <form method="post" action="manage_users.php">
       <input type="checkbox" name="students" id="students"><label for="students">Students</label>
       <input type="checkbox" name="teachers"><label for="teachers">Teachers</label>
       <input type="checkbox" name="admins"><label for="admins">Administrators</label>
       
        <p>First name: <input name="fname" value="<?=$fname?>"><br>
        Last name: <input name="lname" value="<?=$lname?>"><br>
        Email address: <input name="email" type="email" value="<?=$email?>"></p>
            
        <input name="submit" type="submit"> <input type="reset">
    </form>
<?php
include 'footer.php';
?>
</body>
</html>