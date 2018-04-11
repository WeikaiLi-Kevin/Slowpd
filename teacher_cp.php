<?php
/*
teacher_cp.php
Created by Weikai Li
Modified by Slowpd

This is the home page or portal for teachers. This page is reached automatically when a teacher logs in. This page allows teachers to create or edit their availability schedule, either using an online editor, or by uploading a compatible schedule file. A list of upcoming appointments also appears, if applicable. Teachers can accept or reject pending appointments, or cancel accepted appointments. If they do so, a dialog pops up that allows them to send a note to the student, which is sent to the student's email address.
*/

include 'session_include.php';
session_check('teacher');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Portal</title>
<?php
include 'header.php';
?>
<div class="container well" align="center">
    <h1>Welcome, <?=$_SESSION['realname']?></h1>

    <h2 style="font-weight: bold; color: #026342;">Schedule editor</h2>
<?php
    
$filename = "prefs\\{$_SESSION['userid']}\\template.json";
if (!file_exists($filename)) {
    echo '<p class="text-danger">You have not created a schedule for this semester. Students will not be able to request appointments with you until you create an availability schedule.</p>';
}
?>
  <form action="schedule_editor.php" method="post" enctype="multipart/form-data" style=
"max-width: 50%">
       <table class="table table-bordered">
         <tr>
             <td><label for="edit">Edit schedule online</label></td>
              <td><a href="schedule_editor.php"><input type="button" class="btn btn-primary btncheck" value="Edit your schedule"></a></td>
         </tr>
          <tr>
              <td><label for="upload">Upload schedule from file</label></td>
              <td><input type="file" name="upload" id="upload" class="form-control-file" required><br>
              <input class="btn btn-primary" type="submit" name="submit" value="Upload"></td>
          </tr>
       </table>
    </form>

    <h2 style="font-weight: bold; color: #026342;">Upcoming appointments</h2>
<?php
# delete teacher's appointments that are in the past
$query = "DELETE FROM Appointments WHERE TeacherId = ? AND Appt_DateTime < CURDATE();";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $_SESSION['userid']);
$stmt->execute();
$stmt->close();

# check for appointments in the future
$query = 'SELECT a.*, b.FirstName, b.LastName FROM Appointments a JOIN Users b ON (a.StudentId = b.Id) WHERE a.TeacherID = ? ORDER BY Appt_DateTime;';
$stmt = $db->prepare($query);
$stmt->bind_param("s", $_SESSION['userid']);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows == 0)
    echo "<p>You have no booked or pending appointments.</p>";
else {
    echo "<table class=\"table table-bordered\">
    <tr>
        <th>Student</th>
        <th>Time</th>
        <th>Room</th>
        <th>Reason</th>
        <th>Course</th>
        <th>Notes</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>\n";

    while ($row = $result->fetch_assoc()) {
        $date = explode(" ", $row['Appt_DateTime']);
        echo "<tr>
        <td>{$row['FirstName']} {$row['LastName']}</td>
        <td>{$row['Appt_DateTime']}</td>
        <td>{$row['Room']}</td>
        <td>{$row['Reason']}</td>
        <td>{$row['CourseID']}</td>
        <td>{$row['Notes']}</td>
        <td>{$row['Appt_Status']}</td>
        <td><form id=\"{$row['FirstName']}_{$row['LastName']}_{$row['Id']}_{$date[0]}_{$date[1]}\">";
        if ($row['Appt_Status'] == 'pending')
            echo "<input type=\"button\" class=\"btn btn-success\" onclick=\"popupModal(this)\" value=\"Accept\"> &nbsp; <input type=\"button\" class=\"btn btn-danger\" onclick=\"popupModal(this)\" value=\"Reject\">";
        else
            echo "<input type=\"button\" class=\"btn btn-danger\" onclick=\"popupModal(this)\" value=\"Cancel\">";
        
        echo "</form></td>
    </tr>\n";
    }

    echo "</table>";
}
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
                <form action="action_appointment.php" method="post">
                    <div class="modal-body">
                        <table>
                            <tr><td>Student:</td><td id="student"></td></tr>
                            <tr><td>Date:</td><td id="date"></td></tr>
                        </table>
                        <p>Message:</p><textarea rows="10" cols="50" name="notes" value="notes"></textarea>
                    </div>

                    <div class="modal-footer">
                        <input type="submit" id="submit" name="submit" class="btn btn-primary">
                        <input type="hidden" id="appt" name="appt">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
include 'footer.php';
?>
<script type="text/javascript">
/*
Function popupModal
Created by Weikai Li

This function pops up a dialog that allows the teacher to write a note to the student when accepting, rejecting, or cancelling an appointment. Coded in jQuery.
*/
function popupModal(e){
    var vars = $(e).parent().attr('id').split('_'); // creates an array [firstname, lastname, apptId, date, time]
    var action = $(e).val();

    $('#submit').val(action); // 'Accept'/'Reject'/'Cancel'
    $('#student').html(vars[0] + ' ' + vars[1]);
    $('#appt').val(vars[2]);
    $('#date').html(vars[3] + ' ' + vars[4]);
    $('#modaltitle').html(action + " Appointment");

    $('#myModal').modal('show');
}
</script>
</body>
</html>