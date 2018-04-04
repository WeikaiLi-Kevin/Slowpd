<?php
include 'session_include.php';
session_check('teacher');

if ($_POST == [])   # page wasn't reached by POST from teacher_cp.php
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
if ($_POST['submit'] == 'Accept') {
    $query = "UPDATE Appointments SET Notes = ?, Appt_Status = 'accepted' WHERE Id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("si", $_POST['notes'], $_POST['appt']);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();
}
else if ($_POST['submit'] == 'Reject' || $_POST['submit'] == 'Cancel') {
    $query = "DELETE FROM Appointments WHERE Id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $_POST['appt']);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();
}

if ($affected == 0)
    echo "<p>{$_POST['submit']} appointment failed.";
else
    echo "<p>{$_POST['submit']} appointment succeeded.";
        
echo "<p>The following message was sent to the student:</p>

<p>\"{$_POST['notes']}\"</p>";
?>
</div>
<?php
include 'footer.php';
?>    
</body>
</html>