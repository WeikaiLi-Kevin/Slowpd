<?php
include 'session_include.php';
session_check('student');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Portal</title>
<?php
include 'header.php';
?>
<div class="container well" align="center">
    <h1 style="font-weight: bold ;">Welcome, <?=$_SESSION['realname']?></h1>
    
    <h2 style="font-weight: bold ;color:#026342">Upcoming appointments</h2>
<?php
$query = 'SELECT a.*, b.FirstName, b.LastName FROM Appointments a JOIN Users b ON (a.TeacherId = b.Id) WHERE a.StudentID = ? AND Appt_DateTime >= CURDATE() ORDER BY Appt_DateTime;';
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
        <th>Teacher</th>
        <th>Time</th>
        <th>Room</th>
        <th>Reason</th>
        <th>Course</th>
        <th>Notes</th>
        <th>Status</th>
        <th>Actions</th>
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
        <td><input class=\"btn btn-light\" type=\"button\" value=\"Cancel\"></td>
    </tr>\n";
    }

    echo "</table><br>\n";
}

$fname = '';
$lname = '';
$email = '';
?>
    <h3 style="font-weight: bold ;color: #026342">Book appointments</h3>
<?php
if (isset($_POST['submit'])) {
    # using these variables to re-populate fields so that user doesn't have to re-enter every field if they get no search results
    if (isset($_POST['fname'])) $fname = $_POST['fname'];
    if (isset($_POST['lname'])) $lname = $_POST['lname'];
    if (isset($_POST['email'])) $email = $_POST['email'];
    
    if ($fname || $lname || $email) {
        $query = "SELECT * FROM Users WHERE (FirstName = ? OR LastName = ? OR Email = ?) AND UserType='teacher';";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sss", $fname, $lname, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows == 0)
            echo "<p>No users matched your search.</p>";
        else {
            echo "<table class=\"table table-bordered\">
            <caption>Search Results</caption>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Check schedule</th>
            </tr>\n";

            while ($row = $result->fetch_assoc()) {
                $status = $row['ConfirmationHash'] == '' ? 'Registered' : 'Pending';

                echo "<tr>
                <td>{$row['FirstName']} {$row['LastName']}</td>
                <td>{$row['Email']}</td>
                <td><form method=\"post\" action=\"schedule_viewer.php\"><input name=\"submit\" type=\"submit\" value=\"Check\"><input type=\"hidden\" name=\"teacher\" value=\"{$row['Id']}\"><input type=\"hidden\" name=\"teachername\" value=\"{$row['FirstName']} {$row['LastName']}\"></td>
            </tr>\n";
            }

            echo "</table><br>\n";
        }
    }
    else {
        echo '<p class="red">Please fill in at least one field.</p>';
    }
}
?>

   <p>Search for teachers by first name, last name, or email.</p>
    
     <form method="post" action="student_cp.php">
	 <table>
        <tr><td style="font-weight: bold ;">First name: </td><td><input class="form-control" name="fname" value="<?=$fname?>"></td></tr>
        <tr><td style="font-weight: bold ;">Last name: </td><td><input class="form-control" name="lname" value="<?=$lname?>"></td></tr>
        <tr><td style="font-weight: bold ;">Email address: </td><td><input class="form-control" name="email" type="email" value="<?=$email?>"></td></tr>
     </table> 
	 <br>
        <input class="btn btn-success" name="submit" type="submit"> <input class="btn btn-success" type="reset">
    </form>
</div>
    
<?php
include 'footer.php';
?>
</body>
</html>