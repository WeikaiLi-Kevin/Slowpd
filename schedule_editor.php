<?php
include 'session_include.php';
session_check('teacher');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Schedule Editor</title>
<?php
include 'header.php';
    
$dir = "prefs\\{$_SESSION['userid']}";
# use the default template and config if teacher doesn't have a schedule yet
$fconfig = file_exists("$dir\\config.json") ? "$dir\\config.json" : "prefs\\config.json";
$fweek = file_exists("$dir\\template.json") ? "$dir\\template.json" : "prefs\\template.json";

# we need undecoded versions for JS later in the file
$jsonweek = file_get_contents($fweek);
$jsonconfig = file_get_contents($fconfig);

# and decoded versions for PHP
$week = json_decode($jsonweek, true);
$config = json_decode($jsonconfig, true);

$daynames = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
?>
<div class="container well" align="center">
    <h1>Schedule Editor</h1>
    <p>This schedule represents your usual availability for the entire semester. Don't use it to change your availability for the current week; every week will be updated!</p>

        <div class="standings col-sm-12 well">
            <form onsubmit="event.preventDefault(); saveSchedule(); return false;">
				<table class="table table-hover" id="standingstable">
					<thead>
						<tr>
							<th style="width: 16.66%"></th>
							<th style="width: 16.66%"><h3><strong>Monday</strong></h3></th>
							<th style="width: 16.66%"><h3><strong>Tuesday</strong></h3></th>
							<th style="width: 16.66%"><h3><strong>Wednesday</strong></h3></th>
							<th style="width: 16.66%"><h3><strong>Thursday</strong></h3></th>
							<th style="width: 16.66%"><h3><strong>Friday</strong></h3></th>
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
            if ($status == '')
                $button = '<input type="button" class="btn-sm btn-success btncheck" onclick="flipAvailability(this)" value="Available">';
            else
                $button = '<input type="button" class="btn-sm btn-danger btncheck" onclick="flipAvailability(this)" value="Unavailable">';
                                                           
            printf("<td id=\"%s%02d%02d\">%s</td>\n", substr($daynames[$day], 0, 3), $hour, $minute, $button);    
        }
        
        echo "</tr>\n";
    }
}
?>
                        <tr>
                            <td colspan="2"></td>
                            <td class="pull-right"><strong>Meeting room:</strong></td>
                            <td><input id="meetingroom" value="<?=$config['meetingroom']?>" size="8" required></td>
                            <td colspan="2"></td>
                        </tr>
					</tbody>
				</table>
            <p><input type="submit" class="btn btn-primary btncheck" value="Save"></p>
            <p id="save_result"></p>
            </form>
        </div>
<?php
include 'footer.php';
?>
</div>
<script type="text/javascript">
var week = <?=$jsonweek?>;
var config = <?=$jsonconfig?>;

function flipAvailability(e) {
    var parent_id = $(e).parent().attr('id');
    var availability = $(e).val();
    var day = parent_id.substring(0,3); // i.e. mon0800 = mon

    // needed to get day portion of week object
    switch(parent_id.substring(0,3)) {
        case 'mon': case 'fri': day += 'day'; break;
        case 'tue': day = 'tuesday'; break;
        case 'wed': day = 'wednesday'; break;
        case 'thu': day = 'thursday';
    }

    if (availability == 'Available') { // make unavailable
        $(e).val('Unavailable');
        $(e).removeClass('btn-success');
        $(e).addClass('btn-danger');
        week[day][parent_id]['status'] = 'unavailable';
    }
    else { // make available
        $(e).val('Available');
        $(e).removeClass('btn-danger');
        $(e).addClass('btn-success');
        week[day][parent_id]['status'] = '';
    }
}
    
function saveSchedule() {
    config.meetingroom = $('#meetingroom').val();

    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
            document.getElementById('save_result').innerHTML = xmlhttp.responseText;
    }
    xmlhttp.open("POST", "save_schedule.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send('prof=<?=$_SESSION['userid']?>' + '&week=' + JSON.stringify(week) + '&config=' + JSON.stringify(config));
}

</script>
</body>
</html>