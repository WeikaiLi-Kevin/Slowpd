<?php
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