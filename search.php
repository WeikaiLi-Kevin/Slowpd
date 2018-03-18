<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Schedule search</title>
</head>
<body>
<?php
# This page requires $_SESSION check. If not logged in, redirect to
# login.php.
 
# Whatever PHP is going to go in this thing

# Most likely the form submits to itself (this page), and we check the
# $_POST variables to determine if there are results to display or not.
# I prefer this approach to posting to a searchresults.php page,
# which would require returning to the search page if the results
# are unacceptable. Search results display below, and user can click on
# any returned result to see the teacher's schedule. Results link to
# schedule.php and pass variables for given teacher, perhaps in $_SESSION.
?>
   <h1>Schedule search</h1>
   
   <p>Search for teachers by first name, last name, or email.</p>
     <form method="POST">
        First name: <input name="fname"><br>
        Last name: <input name="lname"><br>
        Email address: <input name="email" type="email"><br>
        <input name="submit" type="submit"> <input type="reset">
    </form>
</body>
</html>