<?php
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
    <h1>Welcome, <?=$_SESSION['realname']?></h1>
<?php
    $query = 'SELECT a.*, b.FirstName, b.LastName FROM Appointments a JOIN Users b ON (a.StudentId = b.Id) WHERE a.TeacherID = ? AND Appt_DateTime >= CURDATE() ORDER BY Appt_DateTime;';
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $_SESSION['userid']);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows == 0)
        echo "<p>You have no booked or pending appointments.</p>";
    else {
        echo "<table class=\"border\">
        <caption>Upcoming appointments</caption>
        <tr>
            <th>Student</th>
            <th>Time</th>
            <th>Room</th>
            <th>Reason</th>
            <th>Course</th>
            <th>Notes</th>
            <th>Status</th>
        </tr>\n";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
            <td>{$row['FirstName']} {$row['LastName']}</td>
            <td>{$row['Appt_DateTime']}</td>
            <td>{$row['Room']}</td>
            <td>{$row['Reason']}</td>
            <td>{$row['CourseID']}</td>
            <td>{$row['Notes']}</td>
            <td>{$row['Appt_Status']}</td>
        </tr>\n";
        }
        
        echo "</table>";
    }
?>

<?php
include 'footer.php';
?>
</body>
</html>