<?php
/*
schedule_viewer.php
Created by Dave Sampson
Modified by Slowpd

This page allows students to see a teacher's schedule and request appointments with them during the teacher's office hours. The teacher's office hours (availability to meet) are stored in a file called template.json in the teacher's folder in prefs. The schedule goes from Monday to Friday, from 8 am to 6 pm, and is divided into 30 minute blocks, 100 in total. Blocks that are in the past cannot be booked. Blocks where a teacher has not indicated availability cannot be booked. Blocks where a teacher already has a pending or confirmed appointment cannot be booked. Blocks where a teacher has indicated weekly availability, which have not yet been requested by another student, can be requested by clicking the "Request Appt" button for that time and date. This pops up a dialog with information about the appointment, and the student can indicate which course they wish to discuss, and
write a note about what they want to discuss. This information is emailed to the teacher.
*/

include 'session_include.php';
session_check('student');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Appointment Scheduler</title>
<style>
	td { height: 51px; }
</style>
<?php
include 'header.php';
?>
    <div class="container well" id="container">
<?php
$prof = $_POST['teacher'];
$profName = $_POST['teachername'];
$stud = $_SESSION['userid'];
$filename = "prefs\\$prof\\template.json";
$filename2 = "prefs\\$prof\\config.json";

if (file_exists($filename) && file_exists($filename2)) {
    $configcontents = file_get_contents($filename2);
    $config = json_decode($configcontents, true);
    $meetingroom = $config['meetingroom'];

    # check if student already has an appointment with this teacher
    # prevents a student from booking all of a teacher's slots
    $query = "SELECT COUNT(*) count FROM Appointments WHERE StudentId = ? AND TeacherId = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $_SESSION['userid'], $_POST['teacher']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        echo "<h1>Already booked</h1>
    
    <p>You already have a pending or accepted appointment with this teacher. Please attend this meeting to determine if you need another one. If you need to reschedule, delete your current appointment and request another.</p>
    
</div>";
    }
    else {
        var_dump($row);
        //determines week to show by default
        if(date("l")=="Saturday" || date("l")=="Sunday")
            $mon = strtotime("next monday");
        else
            $mon = strtotime("monday this week");
?>
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

/*
Function getCalendar
Created by Dave Sampson

This function sends a date, teacher' user ID, and teacher's real name via AJAX POST to get_schedule.php. get_schedule creates an availability calendar for the week starting at the date supplied in the date variable, and the contents are added to the DOM for this page.
*/
function getCalendar(){
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
            document.getElementById('container').innerHTML = xmlhttp.responseText;
    }

    xmlhttp.open("POST", "get_schedule.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send('date=' + Math.floor(monday/1000) + '&prof=<?=$prof?>&profname=<?=$profName?>'); 
}

/*
Function popupModal
Created by Dave Sampson

This function pops up a dialog with details of the timeslot the student wishes to book, and allows the user to indicate a course they wish to discuss and add notes about the meeting. Coded in jQuery.
*/
function popupModal(e){
    var parent_id = $(e).parent().attr('id');
    $('#appt').val(parent_id);
    $('#modaltitle').html("Request Appointment");
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
}
else {
    echo "<h1>No schedule for $profName</h1>

    <p>$profName hasn't submitted his or her schedule yet. Please encourage him or her to submit a schedule so that students can book appointments.</p>
    
</div>";
}
include 'footer.php';
?>
</body>
</html>