<?php
include 'session_include.php';
session_check('teacher');

# page wasn't reached by POST from teacher_cp.php
if (!isset($_POST['submit']) || !isset($_POST['appt']) || ($_POST['submit'] != 'Accept' && $_POST['submit'] != 'Reject' && $_POST['submit'] != 'Cancel'))
    header('Location:teacher_cp.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$_POST['submit']?> Appointment</title>
<?php
include 'header.php';
?>
<div class="container well" align="center">
    <h1><?=$_POST['submit']?> Appointment</h1>

<?php
#check that appointment exists and get details about participants
$query = "SELECT a.*,
    b.Email, CONCAT(b.FirstName, ' ', b.LastName) studentname,
    (SELECT CONCAT(FirstName, ' ', LastName) FROM Users WHERE Id = a.TeacherId) teachername,
    (SELECT Email FROM Users WHERE Id = a.TeacherId) teacheremail
    FROM slowpd.Appointments a
    JOIN Users b ON (a.StudentId=b.Id)
    WHERE a.Id = ?;";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $_POST['appt']);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows == 0)
    echo '<p>Appointment has already been cancelled.</p>';
else {
    if ($_POST['submit'] == 'Accept') {
        $query = "UPDATE Appointments SET Notes = ?, Appt_Status = 'accepted' WHERE Id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("si", $_POST['notes'], $_POST['appt']);
    }
    else {
        $query = "DELETE FROM Appointments WHERE Id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $_POST['appt']);
    }
    $stmt->execute();

    if ($stmt->affected_rows == 0)
        echo "<p>{$_POST['submit']} appointment failed.";
    else {
        $apptStatus = strtolower($_POST['submit']) . 'ed';
        $row = $result->fetch_assoc(); # result of earlier query about the appointment

        echo "<p>You have successfully $apptStatus your appointment with {$row['studentname']}.";

        #send email
        $to = $row['Email'];
        $subject = "Algonquin appointment has been $apptStatus";
        $messageTxt .= '<p>Thank you for requesting an appointment through the Algonquin Student-Teacher Appointment Scheduler.</p>';
        $messageTxt .= "<p>Your request for an appointment with {$row['teachername']} at {$row['Appt_DateTime']} has been $apptStatus with the following message:</p>";
        $messageTxt .= "<blockquote>{$_POST['notes']}</blockquote>";
        $message = '<html>';
        $message .= '<body>' . $messageTxt;

        # change this to whatever message you want to send from Algonquin College
        $message .= "<p>Thank you, Team Slowpd.</p>";
        $message .= '</body>';
        $message .= '</html>';
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        # from application email address. change to $row['teacheremail'] if you want email to come from teacher
        $headers .= "From: $EMAIL_FROM";

        mail($to,$subject,$message,$headers);

        echo "<p>The following message was sent to the student:</p>

<div class=\"panel panel-default\">
  <div class=\"panel-body\">$messageTxt</div>
</div>";
    }
    $stmt->close();
?>
</div>
<?php
}
include 'footer.php';
?>    
</body>
</html>