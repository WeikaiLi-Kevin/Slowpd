<?php
session_start();

if (session_status() == PHP_SESSION_ACTIVE) {
    $_SESSION = [];
    session_destroy();
}

header('Location:index.php');
?>