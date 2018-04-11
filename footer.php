<?php
/*
footer.php
Created by Harvey Patterson
Modified by Dave Sampson

This page is included at the bottom of every page, immediately before </body>. This closes the database connection (opened in header.php) and prints the copyright message in a footer.
Closing a database connection that is already closed throws an exception. To prevent mistakes where a coder forgets to close the connection, or closes it on one page and also in an "include"d page, we want the database connection to be opened exactly once (in the header, since every page "include"s it), and closed exactly once (in the footer, since every page "include"s it).
*/

$db->close();
?>
<footer>&copy; 2018 Slowpd. All rights reserved.</footer>