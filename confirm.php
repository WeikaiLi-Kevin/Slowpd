<?php
include 'session_include.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="css/business-frontpage.css" rel="stylesheet">
    <title>Appointment Scheduler</title>
<?php
include 'header.php';

$prof = $_POST["prof"];
$stud = $_POST["stud"];
$myappt = $_POST["appt"];
$day = $_POST["day"];
$mycourse = $_POST["course"];
$mynotes = $_POST["notes"];
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

echo $day;
//DATABASE MySQL

$stmt = $db->prepare("INSERT INTO Appointments (TeacherId, StudentId, Appt_DateTime, Room, Reason, CourseId, Notes, Appt_Status) VALUES (?,?,?,'T306','',?,?,'pending');");
/* bind parameters for markers */
$stmt->bind_param("sssss", $prof, $stud, $datetoinsert, $mycourse, $mynotes);
$stmt->execute();
$stmt->close();
?>
	<div class="container">
		<div class="span10 well">		
			<div align="center">
				<div class="row"><div class="span1"><h1>Appointment Requested</h1></div></div>
				<p>The teacher has been notified of your request. Appointments are confirmed once you receive a confirmation email from the professor.</p>
				<p><strong>Appointment: </strong><?php echo $datetoinsert ?></p>
				<p><strong>Teacher: </strong><?=$_POST['prof']?></p>
				<p><strong>Student: </strong><?=$_POST['stud']?></p>
				<p><strong>Course: </strong><?=$_POST['course']?></p>
				<p><strong>Notes: </strong><?=$_POST['notes']?></p>
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