<?php
/*
logout.php
Created by Mahad Osman
Modified by Jie Wang

There is a link to this page in the navigation bar whenever a user is logged in. Here the SESSION is both cleared and destroyed (to be safe). The page then redirects to the login page without the user ever seeing this page. Therefore, no HTML is rendered.
*/

session_start();

if (session_status() == PHP_SESSION_ACTIVE) {
    $_SESSION = [];
    session_destroy();
}

header('Location:index.php');
?>