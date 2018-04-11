<?php
/*
db_vars.php
Created by Harvey Patterson
Modified by Slowpd

This page exists to hold global constants.
*/

# 4 variables for creating a mysqli object
$DB_HOST = "localhost"; # location of database
$DB_USERNAME = "root";  # database username
$DB_PASSWORD = "mysql"; # database password
$DB_DATABASE = "slowpd";    # database name

# "from" address for emails from the application, probably an administrative or no-reply account
$EMAIL_FROM = 'patt0108@algonquinlive.com';

# base URL for the application, used for links in email notifications.
# for instance, if site can be reached from
# http://www.algonquincollege.com/scheduler, change the address to that
# so that it will be used in the links in emails from the application
# that link to the application
$WEB_HOST = '127.0.0.1:8080/Slowpd';
?>