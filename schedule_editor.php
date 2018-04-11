<?php
/*
schedule_editor.php
Created by Mahad Osman
Modified by Slowpd

This page allows teachers to create an edit a schedule of availability. It is a template for the entire semester, not a schedule of specific dates; typically, teachers have the same office hours and teaching schedule every week.

This page takes a JSON file that is stored on the server, or one that has been uploaded by the teacher (perhaps from a USB key), and creates a user interface with a list of days and times, divided into half hour blocks. Teachers can then toggle each time slot between Available and Unavailable by clicking on the button in each slow. The schedule is saved as a JSON string in a file called template.json in a folder whose name matches the teacher's user id (the first part of email@algonquincollege.com). Teachers can also save other miscellaneous information (so far, just the room number where they have office hours). This information is saved as a JSON string in a file called config.json in the same folder as template.json.
*/

include 'session_include.php';
session_check('teacher');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Schedule Editor</title>
<?php
include 'header.php';
?>
<div class="container well" align="center">
    <h1>Schedule Editor</h1>
<?php
$dir = "prefs\\{$_SESSION['userid']}";
$daynames = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
    
if (isset($_FILES['upload'])) {
    # upload the file to tmp directory
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $_FILES['upload']['tmp_name']) == false) {
        # if failed
        echo '<p class="red">Upload failed.</p>';
        endpage(); # close page
    }
    
    # create object from the file
    $jsonweek = file_get_contents($_FILES['upload']['tmp_name']);
    $week = json_decode($jsonweek, true);
    
    # delete tmp file
    unlink($_FILES['upload']['tmp_name']);
    
    for ($day = 0; $day < 5; $day++) {
        for ($hour = 8; $hour < 18; $hour++) {
            for ($minute = 0; $minute < 31; $minute += 30) {
                 if (isset($week[$daynames[$day]][sprintf("%s%02d%02d", substr($daynames[$day], 0, 3), $hour, $minute)]['status'])) {
                     $status = $week[$daynames[$day]][sprintf("%s%02d%02d", substr($daynames[$day], 0, 3), $hour, $minute)]['status'];
                    if ($status != '' && $status != 'unavailable') {
                        echo '<p class="red">Schedule file contains invalid data.</p>';
                        endpage(); # close page
                    }
                }
                else {
                    echo '<p class="red">Invalid schedule file.</p>';
                    endpage(); # close page
                }
            }
        }
    }

    # uploaded file contained a valid schedule
    
    # we can set $fweek to teacher's dir since we aren't trying to read from it; we already have a schedule from the uploaded file
    $fweek = "$dir\\template.json";

    echo '<p class="red">This is the contents of your uploaded file. <strong>It has not been saved yet.</strong> Please review and make any necessary changes. When you are happy with it, press Save.</p>';
}
else {
    # use the default template if teacher doesn't have a schedule yet
    $fweek = file_exists("$dir\\template.json") ? "$dir\\template.json" : "prefs\\template.json";

    # we need undecoded version for JS later in the file
    $jsonweek = file_get_contents($fweek);
    # and decoded version for PHP
    $week = json_decode($jsonweek, true);
}

# use the default config if teacher doesn't have a schedule yet
$fconfig = file_exists("$dir\\config.json") ? "$dir\\config.json" : "prefs\\config.json";
# we need undecoded version for JS later in the file
$jsonconfig = file_get_contents($fconfig);
# and decoded version for PHP
$config = json_decode($jsonconfig, true);
?>
    <p>This schedule represents your usual availability for the entire semester. Don't use it to change your availability for the current week; every week will be updated!</p>

    <div class="standings col-sm-12 well">
        <form onsubmit="event.preventDefault(); saveSchedule(); return false;">
            <table class="table table-hover" id="standingstable">
                <thead>
                    <tr>
                        <th style="width: 16.66%"></th>
                        <th style="width: 16.66%"><h3><strong>Monday</strong></h3></th>
                        <th style="width: 16.66%"><h3><strong>Tuesday</strong></h3></th>
                        <th style="width: 16.66%"><h3><strong>Wednesday</strong></h3></th>
                        <th style="width: 16.66%"><h3><strong>Thursday</strong></h3></th>
                        <th style="width: 16.66%"><h3><strong>Friday</strong></h3></th>
                    </tr>
                </thead>
                <tbody>
<?php
for ($hour = 8; $hour < 18; $hour++) {
    for ($minute = 0; $minute < 31; $minute += 30) {
        printf("<tr>
                <td><strong>%02d:%02d</strong></td>\n", $hour, $minute);

        for ($day = 0; $day < 5; $day++) {
            $status = $week[$daynames[$day]][sprintf("%s%02d%02d", substr($daynames[$day], 0, 3), $hour, $minute)]['status'];
            if ($status == '')
                $button = '<input type="button" class="btn-sm btn-success btncheck" onclick="flipAvailability(this)" value="Available">';
            else
                $button = '<input type="button" class="btn-sm btn-danger btncheck" onclick="flipAvailability(this)" value="Unavailable">';

            printf("<td id=\"%s%02d%02d\">%s</td>\n", substr($daynames[$day], 0, 3), $hour, $minute, $button);    
        }

        echo "</tr>\n";
    }
}
?>
                    <tr>
                        <td colspan="2"></td>
                        <td class="pull-right"><strong>Meeting room:</strong></td>
                        <td><input id="meetingroom" value="<?=$config['meetingroom']?>" size="8" required></td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        <p><input type="submit" class="btn btn-primary btncheck" value="Save"></p>
        <p id="save_result"></p>
        </form>
    </div>
<?php
include 'footer.php';
?>
</div>
<script type="text/javascript">
var week = <?=$jsonweek?>;
var config = <?=$jsonconfig?>;

/*
Function flipAvailability
Created by Mahad Osman

This function toggles the teacher's availability from Available to Unavailable and vice versa. This change is displayed on the button, but also updates the corresponding time block in the "week" object, which represents the teacher's schedule, so that it can be saved to template.json when the user presses Save. Coded in jQuery.
*/
function flipAvailability(e) {
    var parent_id = $(e).parent().attr('id');
    var availability = $(e).val();
    var day = parent_id.substring(0,3); // i.e. mon0800 = mon

    // needed to get day portion of week object
    switch(parent_id.substring(0,3)) {
        case 'mon': case 'fri': day += 'day'; break;
        case 'tue': day = 'tuesday'; break;
        case 'wed': day = 'wednesday'; break;
        case 'thu': day = 'thursday';
    }

    if (availability == 'Available') { // make unavailable
        $(e).val('Unavailable');
        $(e).removeClass('btn-success');
        $(e).addClass('btn-danger');
        week[day][parent_id]['status'] = 'unavailable';
    }
    else { // make available
        $(e).val('Available');
        $(e).removeClass('btn-danger');
        $(e).addClass('btn-success');
        week[day][parent_id]['status'] = '';
    }
}

/*
Function saveSchedule
Created by Mahad Osman

This function sends two JavaScript objects -- week and config -- which represent the teacher's schedule and meeting room, via AJAX POST to save_schedule.php to be saved in template.json and config.json, respectively.
*/
function saveSchedule() {
    config.meetingroom = $('#meetingroom').val();

    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
            document.getElementById('save_result').innerHTML = xmlhttp.responseText;
    }
    xmlhttp.open("POST", "save_schedule.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send('prof=<?=$_SESSION['userid']?>' + '&week=' + JSON.stringify(week) + '&config=' + JSON.stringify(config));
}
</script>

</body>
</html>