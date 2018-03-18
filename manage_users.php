<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage users</title>
</head>
<body>
<?php
# This page requires $_SESSION check. If not logged in, redirect to
# login.php.

# Whatever PHP is going to go in this thing.

# Most likely the form submits to itself (this page), and we check the
# $_POST variables to determine if there are results to display or not.
# I prefer this approach to posting to a searchresults.php page,
# which would require returning to the search page if the results
# are unacceptable.
?>
    <h1>Manage users</h1>
    
   <p>Search for users by first name, last name, or email.</p>
    
     <form method="POST">
       <input type="checkbox" name="students" id="students" checked><label for="students">Students</label>
       <input type="checkbox" name="teachers" checked><label for="teachers">Teachers</label>
       <input type="checkbox" name="admins" checked><label for="admins">Administrators</label>
       
        <p>First name: <input name="fname"><br>
        Last name: <input name="lname"><br>
        Email address: <input name="email" type="email"></p>
            
        <input name="submit" type="submit"> <input type="reset">
    </form>
</body>
</html>