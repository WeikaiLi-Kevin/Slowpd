<?php
session_start();

# if not logged in, sent to login page
if ($_SESSION == [])
    header('Location:index.php');

# session_check by Harvey Patterson
# Many pages should only be accessed by specific user types. Use this function to check if the user's usertype matches the usertype required for this page.
# If not, the user is logged out and sent to the login page.
function session_check($usertype) {
    if ($_SESSION['usertype'] != $usertype)
        header('Location:logout.php');
}
?>