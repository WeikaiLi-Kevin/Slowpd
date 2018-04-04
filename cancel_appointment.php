<?php
include 'session_include.php';
session_check('student');

if ($_POST == [])   # page wasn't reached by POST from student_cp.php
    header('Location:student_cp.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cancel appointment</title>
<?php
include 'header.php';
?>
<div class="container well" align="center">
  <h1>Cancel appointment</h1>
<?php
$query = 'DELETE FROM Appointments WHERE Id = ?';
$stmt = $db->prepare($query);
$stmt->bind_param("s", $_POST['appointment']);
$stmt->execute();

if ($stmt->affected_rows == 1)
    echo "<p>Appoinmtment cancelled.</p>";
else
    echo "<p>Appointment cancellation failed.</p>";

$stmt->close();
$_POST = [];

echo '<p>Return to <a href="student_cp.php">Control Panel</a>.</p>
</div>';

include 'footer.php';
?>
</body>
</html>