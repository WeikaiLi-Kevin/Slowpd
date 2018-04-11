<?php
/*
session_include.php
Created by Harvey Patterson
Modified by Slowpd

This page is "include"d into every page where a user must be logged in. At present, this is every page except for the login and registration pages. This page loads the SESSION variables. At present, the SESSION stores the following variables:

userid: the user's id in the database
usertype: the user's account type (student, teacher, admin)
realname: the user's real name

If a page that has this file "include"d is reached when a user is not logged in (no SESSION variables), the user is redirected to the login page.

Furthermore, most pages can only be accessed by users of a specific usertype. If a page should only be accessed by a specific user type, call session_check and pass the required usertype as the argument. For instance, if a user must be an admin to visit a page, call session_check('admin') to check $_SESSION['usertype'] to ensure that the user is an administrator. If a user attempts to access a page that they do not have the right to visit (wrong user type), they are logged out. It would be simple to also log this information in the database or a log file if desired.
*/

session_start();

# if not logged in, send to login page
if ($_SESSION == [])
    header('Location:index.php');

/*
Fucntion session_check
Created by Harvey Patterson

Many pages should only be accessed by specific user types. Use this function to check if the user's usertype matches the usertype required for this page. If not, the user is logged out and sent to the login page.
*/
function session_check($usertype) {
    if ($_SESSION['usertype'] != $usertype)
        /* This code could be used to log unauthorized accesses in the database. Create a table called unauthorized by uncommenting and running the SQL query that creates the "unauthorized" table in the SQL files included with this hand-in.

        include 'db_vars.php';

        $query = 'INSERT INTO unauthorized (userid, page, datetime) VALUES (?, ?, ?);
        $stmt = $db->prepare($query);
        $stmt->bind_param("sss", $_SESSION['userid'], $_SERVER['REQUEST_URI'], $date = date());
        $stmt->execute();
        $stmt->close();
        $db->close();
*/
        header('Location:logout.php');
}
?>