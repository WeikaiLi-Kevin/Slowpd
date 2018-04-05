<?php
if ($_POST == [])   # page wasn't reached by AJAX POST from schedule_viewer.php
    header('Location:student_cp.php');

include 'db_vars.php';
$db = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
$db->set_charset("utf8");


# calling page provides Monday for week to check
$mon = $_POST['date'];   # date in seconds from Unix epoch
$tue = $mon + 86400;    # 86400 seconds per day
$wed = $tue + 86400;
$thu = $wed + 86400;
$fri = $thu + 86400;

$days = [date("mdY",$mon), date("mdY",$tue), date("mdY",$wed), date("mdY",$thu), date("mdY",$fri)];
$daynames = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

$filename = "prefs\\{$_POST['prof']}\\template.json";
$week = json_decode(file_get_contents($filename), true);

//Grabbing user prefs
$query = "SELECT Appt_Status, Appt_DateTime FROM Appointments WHERE TeacherId = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $_POST['prof']);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$db->close();


if ($result->num_rows > 0) {
    // output data of each row
        var_dump($days);
    while($row = $result->fetch_assoc()) {
        //Piece together string for JSON
        $dbdate = $row['Appt_DateTime'];
        $dayname = strtolower(date("l", strtotime($dbdate)));
        $string=strtolower(date("DHi", strtotime($dbdate)));
        //Configure JSON
        if(in_array(date("mdY",strtotime($dbdate)),$days)){
            if ($row['Appt_Status'] == "pending"){
                $week[$dayname][$string]['status'] = 'pending';
            }
            else {  // "accepted"
                $week[$dayname][$string]['status'] = 'booked';
            }
        }
    }
}
$myJSON = json_encode($week);
?>
        <div class="standings col-sm-12 well">
            <div align="center" class="col-sm-12">
                <h1>Schedule for <?=$_POST['profname']?></h1>
                <input type="button" class="btn btn-light" value="Previous week"<?php if ($mon > time()) { echo 'onclick="monday -= 604800000; getCalendar();"'; } else { echo 'disabled'; }?>> 
                <input type="button" class="btn btn-light" value="Next week" onclick="monday += 604800000; getCalendar();">
            </div>

				<table class="table table-hover" id="standingstable">
					<thead>
						<tr>
							<th style="width: 16.66%"></th>
							<th style="width: 16.66%"><h3><strong>Mon <?=date("m/d", $mon)?></strong></h3></th>
							<th style="width: 16.66%"><h3><strong>Tue <?=date("m/d", $tue)?></strong></h3></th>
							<th style="width: 16.66%"><h3><strong>Wed <?=date("m/d", $wed)?></strong></h3></th>
							<th style="width: 16.66%"><h3><strong>Thu <?=date("m/d", $thu)?></strong></h3></th>
							<th style="width: 16.66%"><h3><strong>Fri <?=date("m/d", $fri)?></strong></h3></th>
						</tr>
					</thead>
					<tbody>
<?php
for ($hour = 8; $hour < 18; $hour++) {
    for ($minute = 0; $minute < 31; $minute += 30) {
        printf("<tr>
                <td><strong>%02d:%02d</strong></td>\n", $hour, $minute);
        
        for ($day = 0; $day < 5; $day++) {
            $status = $week[$daynames[$day]][sprintf("%s%02d%02d", substr($daynames[$day], 0, 3), $hour, $minute)]['status'];
            $time = $mon + (86400 * $day) + (3600 * $hour) + (60 * $minute);
            if ($time < time())
                $button = '<strong style="color: maroon">Past</strong>';
            else if ($status == '')
                $button = '<button type="button" class="btn-sm btn-primary btncheck" onclick="popupModal(this)">Request Appt</button>';
            else if ($status == 'pending')
                $button = '<strong style="color: green">Pending</strong>';
            else if ($status == 'booked')
                $button = '<strong class="text-warning">Booked</strong>';
            else if ($status == 'unavailable')
                $button = '<strong class="text-danger">Unavailable</strong>';
            else
                $button = '<strong style="color: black">Unknown</strong>';
                                                           
            printf("<td id=\"%s%02d%02d\">%s</td>\n", substr($daynames[$day], 0, 3), $hour, $minute, $button);    
        }
        
        echo "</tr>\n";
    }
}
?>
					</tbody>
				</table>
            </div>