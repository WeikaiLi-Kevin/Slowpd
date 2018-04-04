<?php
if ($_GET == [])   # page wasn't reached by AJAX GET from schedule_viewer.php
    header('Location:student_cp.php');

include 'db_vars.php';

$db = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
$db->set_charset("utf8");


# calling page provides Monday for week to check
$mon = $_GET['date'];   # date in seconds from Unix epoch
$tue = $mon + 86400;    # 86400 seconds per day
$wed = $tue + 86400;
$thu = $wed + 86400;
$fri = $thu + 86400;

$days = [date("mdY",$mon), date("mdY",$tue), date("mdY",$wed), date("mdY",$thu), date("mdY",$fri)];
$daynames = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

$filename = "prefs\\{$_GET['prof']}\\template.json";
$temp1 = file_get_contents($filename);

$week = json_decode($temp1, true);

//Grabbing user prefs
$query = "SELECT Appt_Status, Appt_DateTime FROM Appointments WHERE TeacherId = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $_GET['prof']);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$db->close();

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        //Piece together string for JSON
        $dbdate = $row['Appt_DateTime'];

        $dayname = strtolower(date("l", strtotime($dbdate)));
        $string=strtolower(date("DGi", strtotime($dbdate)));
        //Configure JSON
        if ($row['Appt_Status'] == "pending"){
            if(in_array(date("mdY",strtotime($dbdate)),$days)){
                $week[$dayname][$string]['status'] = 'pending';
            }
        }
    }
}
$myJSON = json_encode($week);
?>
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
                <td><strong>%02d:%02d</strong></td>", $hour, $minute);
        
        for ($day = 0; $day < 5; $day++) {
            $status = $week[$daynames[$day]][sprintf("%s%02d%02d", substr($daynames[$day], 0, 3), $hour, $minute)]['status'];
            if ($status == '')
                $button = '<button type="button" class="btn-sm btn-primary btncheck" onclick="popupModal(this)">Request Appt</button>';
            else if ($status == 'pending')
                $button = '<strong style="color: green">Pending</strong>';
            else if ($status == 'unavailable')
                $button = '<strong style="color: maroon">Unavailable</strong>';
            else
                $button = '<strong style="color: black">Unknown</strong>';
                                                           
            printf("<td id=\"%s%02d%02d\">%s</td>", substr($daynames[$day], 0, 3), $hour, $minute, $button);    
        }
        
        echo "</tr>\n";
    }
}
?>
					</tbody>
				</table>