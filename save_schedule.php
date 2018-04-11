<?php
/*
save_schedule.php
Created by Mahad Osman
Modified by Slowpd

This page can never be visited. It is fetched by AJAX POST from schedule_editor.php. The page receives a teacher's schedule in the form of a JSON string. If the teacher has never created a schedule, they will not have a folder on the system, therefore we will create one. The schedule is saved as template.json in a folder whose name matches the teacher's user id (the first part of email@algonquincollege.com). Teachers can also save other miscellaneous information (so far, just the room number where they have office hours). This information is saved as a JSON string in a file called config.json in the same folder as template.json.
*/

if ($_POST == [])   # page wasn't reached by AJAX POST from schedule_editor.php
    header('Location:teacher_cp.php');

$dir = "prefs\\{$_POST['prof']}";
$week = json_decode($_POST['week']);
$config = json_decode($_POST['config']);

# create the directory if teacher doesn't have a schedule
if (!is_dir($dir))
    mkdir($dir, 0644);

# will create files if they doesn't exist
if (file_put_contents("$dir\\template.json", json_encode($week)) && file_put_contents("$dir\\config.json", json_encode($config)))
    echo "Schedule saved.";
else
    echo "Schedule save failed.";
?>