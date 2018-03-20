# Slowpd

Read this! Read it!

Every page that requires you to be logged in -- currently everything but index.php and register.php -- should include 'session_include.php' on the first line to kick anyone not logged in to the login page. If only one type of user should be on this page -- probably always the case -- the second line should be "session_check([usertype]);" to kick anyone not of that user level off the page. This is for security, guys. Do it!

$db should be opened only once and closed exactly once. To make sure that that always happens, I open it in header.php and close it in footer.php. Makes sense? *EVERY* page should include header.php before closing </head> (which you no longer need to do). *EVERY* page should include footer.php before </body>. Don't make me come in there!