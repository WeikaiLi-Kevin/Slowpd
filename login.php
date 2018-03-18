<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
<?php
# Whatever PHP is going in this thing
   
# Most likely the form submits to this page and checks the $_POST
# variables to determine if the user has pressed Submit or not. If so,
# checks database for username and password. If user authenticates,
# redirect to search.php for students, teacher.php for teachers,
# admin.php for admins. If not, post invalid username/password message.
    
?>
   <h1>Appointment Scheduler</h1>
   
    <form method="POST">
        Username: <input name="username"><br>
        Password: <input name="password" type="password"><br>
        <input name="submit" type="submit"> <input type="reset">
    </form>
        
</body>
</html>