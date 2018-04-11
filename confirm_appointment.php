<?php
/*
confirm_appointment.php
Created by Dave Sampson
Modified by Slowpd

This page is reached by POST when a student requests an appointment with a teacher from schedule_viewer.php. An email is sent to the teacher with the student's notes.
*/

include 'session_include.php';
session_check('student');

if ($_POST == [])   # page wasn't reached by POST from schedule_viewer.php
    header('Location:student_cp.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="css/business-frontpage.css" rel="stylesheet">
    <title>Appointment Confirmation</title>
<?php
include 'header.php';

$myappt = $_POST["appt"];
$day = $_POST["day"];
$mycourse = $_POST["course"];
$reason = $_POST["reason"];
$meetingroom = $_POST["meetingroom"];
$split1= substr($myappt,0,3);
$split2= substr($myappt,3,2);
$split3= substr($myappt,5,2);
$appttime=$split2;
$appttime.=":";
$appttime.=$split3;
$appttime.=":00";

$appttime=$split2;
$appttime.=":";
$appttime.=$split3;
$appttime.=":00";
$datetoinsert=$day;
$datetoinsert.=" ";
$datetoinsert.=$appttime;

# add appointment to database
$query = "INSERT INTO Appointments (TeacherId, StudentId, Appt_DateTime, Room, Reason, CourseId, Notes, Appt_Status) VALUES (?,?,?,?,?,?,'','pending');";
$stmt = $db->prepare($query);
$stmt->bind_param("ssssss", $_POST['prof'], $_SESSION['userid'], $datetoinsert, $meetingroom, $reason, $mycourse);
$stmt->execute();
$stmt->close();

$query = "SELECT Email from Users WHERE Id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $_POST['prof']);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$row = $result->fetch_assoc();

#send email
$to = $row['Email'];
$subject = "Appointment request from {$_SESSION['realname']}";
$message = '<html>';
$message .= '<body>';
$message .= "<p>{$_SESSION['realname']} has requested an appointment with you with this message:</p>";
$message .= "<blockquote>$reason</blockquote>";
$message .= "<p>Please log into the <a href=\"$WEB_HOST\">Algonquin College Student-Teacher Appointment Scheduler</a> to see your pending appointment requests.</p>";
$message .= "<p>If the link above doesn't work, copy this address into your browser: $WEB_HOST</p>";
# change this to whatever message you want to send from Algonquin College
$message .= "<p>Thank you, Team Slowpd.</p>";
$message .= '</body>';
$message .= '</html>';
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$headers .= "From: $EMAIL_FROM";

mail($to,$subject,$message,$headers);
?>
	<div class="container">
		<div class="span10 well">		
			<div align="center">
				<div class="row"><div class="span1"><h1>Appointment Requested</h1></div></div>
				<p>The teacher has been notified of your request. Appointments are confirmed once you receive a confirmation email from the teacher.</p>
				<p><strong>Appointment: </strong><?=$datetoinsert?></p>
				<p><strong>Teacher: </strong><?=$_POST['profname']?></p>
				<p><strong>Student: </strong><?=$_SESSION['realname']?></p>
				<p><strong>Course: </strong><?=$mycourse?></p>
				<p><strong>Reason: </strong><?=$reason?></p>
				<p><strong>Meeting Room: </strong><?=$meetingroom?></p>
				<br>
				<button type="button" class="btn btn-primary" onClick="location.href='student_cp.php';">OK</button>

			</div>
		</div>
    </div>
<?php
include 'footer.php';
?>
</body>
</html>