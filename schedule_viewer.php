<?php
include 'session_include.php';
session_check('student');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Appointment Scheduler</title>
<style>
	td {
   height: 51px;
}
</style>
<?php
include 'header.php';

$prof = $_POST['teacher'];
$profName = $_POST['teachername'];
$stud = $_SESSION['userid'];
$filename = "prefs\\$prof\\template.json";
$filename2 = "prefs\\$prof\\config.json";

$configcontents = file_get_contents($filename2);
$config = json_decode($configcontents, true);
$meetingroom = $config['meetingroom'];

if (file_exists($filename)) {
    //determines week to show by default
    if(date("l")=="Saturday" || date("l")=="Sunday")
        $mon = strtotime("next monday");
    else
        $mon = strtotime("monday this week");
?>
    <div class="container">
        <div class="standings col-sm-12 well">
        <div id="ajax_table">
        </div>
    </div>
    
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
        <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modaltitle">Request Appointment</h4>
                </div>
                <form action="confirm_appointment.php" method="post">
                    <div class="modal-body">
                        <table>
                            <tr><td>Professor:</td><td><?=$profName?></td></tr>
                            <tr><td>Student:</td><td><?=$_SESSION['realname']?></td></tr>
                            <tr><td>Course:</td><td><input type="text" name="course"></td></tr>
                            <tr><td>Date:</td><td id="date"></td></tr>
                        </table>
                        <p>Reason:</p><textarea rows="10" cols="50" name="reason" value="reason"></textarea>
                    </div>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary">
                        <input type="hidden" id="appt" name="appt" value="">
                        <input type="hidden" id="stud" name="stud" value="<?=$stud?>">
                        <input type="hidden" id="prof" name="prof" value="<?=$prof?>">
                        <input type="hidden" id="profname" name="profname" value="<?=$profName?>">
                        <input type="hidden" id="day" name="day" value="">
						<input type="hidden" id="meetingroom" name="meetingroom" value="<?=$meetingroom?>">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script type="text/javascript">	
// super important variable! monday is what we use to move forward and backwards through the calendar.
monday = <?=$mon * 1000?>; // * 1000 because JavaScript tracks time in milliseconds since Unix epoch, PHP uses seconds
getCalendar();

function getCalendar(){
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
            document.getElementById('ajax_table').innerHTML = xmlhttp.responseText;
    }
    xmlhttp.open("GET", 'get_schedule.php?date=' + Math.floor(monday/1000) + '&prof=<?=$prof?>&profname=<?=$profName?>', true); // have to convert from milliseconds back to seconds
    xmlhttp.send();
}

function popupModal(e){
    var parent_id = $(e).parent().attr('id');
    $('#appt').val(parent_id);
    $('#modaltitle').html("Request Appointment: " + parent_id);
    var abbr=parent_id.substring(0,3);

    days = ['mon', 'tue', 'wed', 'thu', 'fri'];

    for (var i=0; i<5; i++) {
        if (abbr == days[i]) {
            date = new Date(monday + (i * 86400000)); // gets milliseconds from epoch for selected day
            ymd = date.toISOString().substring(0,10); // converts '2018-03-29T06:08:16.352Z' to '2018-03-29'

            $('#date').html(ymd);
            $('#day').val(ymd);
        }
    }

    $('#myModal').modal('show'); 
}
</script>
<?php
}
else {
    echo "<h1>No schedule for $profName</h1>
    
    <p>$profName hasn't submitted his or her schedule yet. Please encourage him or her to submit a schedule so that students can book appointments.</p>";
}

include 'footer.php';
?>
</body>
</html>