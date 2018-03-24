<?php
include 'session_include.php';
session_check('student');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Appointment Scheduler</title>
    <link href="css/business-frontpage.css" rel="stylesheet">
<style>
	td {
   height: 51px;
}
</style>
<?php
include 'header.php';

$prof = $_POST['teacher'];
$stud = $_SESSION['userid'];
$filename = "prefs\\$prof\\template.json";

# we can be sure that teacher is in the database, else student couldn't have gotten to this page
$query = "SELECT FirstName, LastName FROM Users WHERE Id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $prof);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$row = $result->fetch_assoc();
$profName = "{$row['FirstName']} {$row['LastName']}";

if (file_exists($filename)) {


    $temp1 = file_get_contents($filename);

    //DETERMINING DAYS
    if(date("l")=="Saturday" || date("l")=="Sunday"){
        $monday = date("mdY",strtotime("next monday"));
        $tuesday = date("mdY",strtotime("next tuesday"));
        $wednesday = date("mdY",strtotime("next wednesday"));
        $thursday = date("mdY",strtotime("next thursday"));
        $friday = date("mdY",strtotime("next friday"));

        $mon = date("m/d",strtotime("next monday"));
        $tue = date("m/d",strtotime("next tuesday"));
        $wed = date("m/d",strtotime("next wednesday"));
        $thu = date("m/d",strtotime("next thursday"));
        $fri = date("m/d",strtotime("next friday"));

        $mondayFormat = date("Y-m-d",strtotime("next monday"));
        $tuesdayFormat = date("Y-m-d",strtotime("next tuesday"));
        $wednesdayFormat = date("Y-m-d",strtotime("next wednesday"));
        $thursdayFormat = date("Y-m-d",strtotime("next thursday"));
        $fridayFormat = date("Y-m-d",strtotime("next friday"));
    }else if(date("l")=="Monday" || 
        date("l")=="Tuesday" || 
        date("l")=="Wednesday" || date("l")=="Thursday" || 
        date("l")=="Friday"){
            $monday = date("mdY",strtotime("monday this week"));
            $tuesday = date("mdY",strtotime("tuesday this week"));
            $wednesday = date("mdY",strtotime("wednesday this week"));
            $thursday = date("mdY",strtotime("thursday this week"));
            $friday = date("mdY",strtotime("friday this week"));

            //For headers in table
            $mon = date("m/d",strtotime("monday this week"));
            $tue = date("m/d",strtotime("tuesday this week"));
            $wed = date("m/d",strtotime("wednesday this week"));
            $thu = date("m/d",strtotime("thursday this week"));
            $fri = date("m/d",strtotime("friday this week"));

            //Selected date to pass to confirm window
            $mondayFormat = date("Y-m-d",strtotime("monday this week"));
            $tuesdayFormat = date("Y-m-d",strtotime("tuesday this week"));
            $wednesdayFormat = date("Y-m-d",strtotime("wednesday this week"));
            $thursdayFormat = date("Y-m-d",strtotime("thursday this week"));
            $fridayFormat = date("Y-m-d",strtotime("friday this week"));
        }
        $days = array($monday, $tuesday, $wednesday, $thursday, $friday); 
        $formattedDate = array("$mondayFormat", "$tuesdayFormat", "$wednesdayFormat", "$thursdayFormat", "$fridayFormat");
        $today = date("m/d/Y",strtotime("today"));
        $time = date("h:i:s",strtotime("now"));

    //$template = file_get_contents('template.json');
    $template = $temp1;
    $week = json_decode($template, true);

    //Grabbing user prefs
    $query = "SELECT Appt_Status, Appt_DateTime FROM Appointments WHERE TeacherId = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $prof);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            //Piece together string for JSON
            $dbdate = $row['Appt_DateTime'];
            $dbdate = $dbdate;
            $newdate = strtolower(date("D", strtotime($dbdate)));
            $dayname = strtolower(date("l", strtotime($dbdate)));

            $dbtime = $row['Appt_DateTime'];
            $newtime = date("Gi", strtotime($dbtime));

            $string=$newdate.$newtime;
            $status=$row['Appt_Status'];
            //Configure JSON
            if($status=="pending"){

                if(in_array(date("mdY",strtotime($dbdate)),$days)){
                $week[$dayname][$string]['status'] = 'pending';
                }
            }
        }
    }
    else {
    }
    $myJSON = json_encode($week);
?>
<script>	
function popupModal(e){
			var parent_id = $(e).parent().attr('id');
			console.log(parent_id);
			document.getElementById("appt").value=parent_id;
			document.getElementById("modaltitle").innerHTML= "Request Appointment: " + parent_id;
			var abbr=parent_id.substring(0,3);
			console.log(parent_id.substring(0,3));
			if(abbr=="mon"){
				document.getElementById("date").innerHTML= "<?php echo $formattedDate[0] ?>";
				document.getElementById('day').value="<?php echo $formattedDate[0] ?>";
			}else if(abbr=="tue"){
				document.getElementById("date").innerHTML= "<?php echo $formattedDate[1] ?>";	
				document.getElementById('day').value="<?php echo $formattedDate[1] ?>";				
			}else if(abbr=="wed"){
				document.getElementById("date").innerHTML= "<?php echo $formattedDate[2] ?>";
				document.getElementById('day').value="<?php echo $formattedDate[2] ?>";
			}else if(abbr=="thu"){
				document.getElementById("date").innerHTML= "<?php echo $formattedDate[3] ?>";
				document.getElementById('day').value="<?php echo $formattedDate[3] ?>";
			}else if(abbr=="fri"){
				document.getElementById("date").innerHTML= "<?php echo $formattedDate[4] ?>";	
				document.getElementById('day').value="<?php echo $formattedDate[4] ?>";
			}
			
			$('#myModal').modal('show'); 
}	
	
//function confirmRequest(){
//	window.location="confirm.php";
//}

	$(document).ready(function(){

		//Testing
	var week = <?php echo json_encode($myJSON) ?>;
	week= JSON.parse(week);
	var monday = week.monday;
	var tuesday = week.tuesday;
	var wednesday = week.wednesday;
	var thursday = week.thursday;	
	var friday = week.friday;
	var daysoftheweek = [monday,tuesday,wednesday,thursday,friday];

	
daysoftheweek.forEach(function(element) {
		for (var key in element) {
			if (element.hasOwnProperty(key)) {
				if(element[key]["status"]==""){
				//document.getElementById(key).innerHTML = '<button type="button" class="btn-sm btn-primary btncheck" data-toggle="modal" data-target="#myModal">Request Appt</button>';
				document.getElementById(key).innerHTML = '<button type="button" class="btn-sm btn-primary btncheck" onClick="popupModal(this)">Request Appt</button>';				
				}else if(element[key]["status"]=="pending"){
				document.getElementById(key).innerHTML = '<strong style="color: green">Pending</strong>';			
				}else if(element[key]["status"]=="unavailable"){
					document.getElementById(key).innerHTML = '<strong style="color: maroon">Unavailable</strong>';	
				}
				else{
				document.getElementById(key).innerHTML = '<strong style="color: black">Unknown</strong>';
				//document.getElementById(key).innerHTML = element[key]["course"];
				}
			}
		}
	});
});
	</script>

	<div class="container">
		<div class="standings col-sm-12 well">
			<div align="center" class="col-sm-12">
				<h1>Schedule for <?=$profName?></h1>
			</div>
			<div>			
				<table class="table table-hover" id="standingstable">
					<thead>
						<tr>
							<th style="width: 16.66%"></th>
							<th style="width: 16.66%"><h3><strong>Mon <?php echo $mon ?></strong></h3></th>
							<th style="width: 16.66%"><h3><strong>Tue <?php echo $tue ?></strong></h3></th>
							<th style="width: 16.66%"><h3><strong>Wed <?php echo $wed ?></strong></h3></th>
							<th style="width: 16.66%"><h3><strong>Thu <?php echo $thu ?></strong></h3></th>
							<th style="width: 16.66%"><h3><strong>Fri <?php echo $fri ?></strong></h3></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><strong>08:00</strong></td>
							<td id = "mon0800"></td>
							<td id = "tue0800"></td>
							<td id = "wed0800"></td>
							<td id = "thu0800"></td>
							<td id = "fri0800"></td>
						</tr>
						<tr>
							<td><strong>08:30</strong></td>
							<td id = "mon0830"></td>
							<td id = "tue0830"></td>
							<td id = "wed0830"></td>
							<td id = "thu0830"></td>
							<td id = "fri0830"></td>
						</tr>
						<tr>
							<td><strong>09:00</strong></td>
							<td id = "mon0900"></td>
							<td id = "tue0900"></td>
							<td id = "wed0900"></td>
							<td id = "thu0900"></td>
							<td id = "fri0900"></td>
						</tr>
						<tr>
							<td><strong>09:30</strong></td>
							<td id = "mon0930"></td>
							<td id = "tue0930"></td>
							<td id = "wed0930"></td>
							<td id = "thu0930"></td>
							<td id = "fri0930"></td>
						</tr>
						<tr>
							<td><strong>10:00</strong></td>
							<td id = "mon1000"></td>
							<td id = "tue1000"></td>
							<td id = "wed1000"></td>
							<td id = "thu1000"></td>
							<td id = "fri1000"></td>
						</tr>
						<tr>
							<td><strong>10:30</strong></td>
							<td id = "mon1030"></td>
							<td id = "tue1030"></td>
							<td id = "wed1030"></td>
							<td id = "thu1030"></td>
							<td id = "fri1030"></td>
						</tr>
						<tr>
							<td><strong>11:00</strong></td>
							<td id = "mon1100"></td>
							<td id = "tue1100"></td>
							<td id = "wed1100"></td>
							<td id = "thu1100"></td>
							<td id = "fri1100"></td>
						</tr>
						<tr>
							<td><strong>11:30</strong></td>
							<td id = "mon1130"></td>
							<td id = "tue1130"></td>
							<td id = "wed1130"></td>
							<td id = "thu1130"></td>
							<td id = "fri1130"></td>
						</tr>
						<tr>
							<td><strong>12:00</strong></td>
							<td id = "mon1200"></td>
							<td id = "tue1200"></td>
							<td id = "wed1200"></td>
							<td id = "thu1200"></td>
							<td id = "fri1200"></td>
						</tr>
						<tr>
							<td><strong>12:30</strong></td>
							<td id = "mon1230"></td>
							<td id = "tue1230"></td>
							<td id = "wed1230"></td>
							<td id = "thu1230"></td>
							<td id = "fri1230"></td>
						</tr>
						<tr>
							<td><strong>13:00</strong></td>
							<td id = "mon1300"></td>
							<td id = "tue1300"></td>
							<td id = "wed1300"></td>
							<td id = "thu1300"></td>
							<td id = "fri1300"></td>
						</tr>
						<tr>
							<td><strong>13:30</strong></td>
							<td id = "mon1330"></td>
							<td id = "tue1330"></td>
							<td id = "wed1330"></td>
							<td id = "thu1330"></td>
							<td id = "fri1330"></td>
						</tr>
						<tr>
							<td><strong>14:00</strong></td>
							<td id = "mon1400"></td>
							<td id = "tue1400"></td>
							<td id = "wed1400"></td>
							<td id = "thu1400"></td>
							<td id = "fri1400"></td>
						</tr>
						<tr>
							<td><strong>14:30</strong></td>
							<td id = "mon1430"></td>
							<td id = "tue1430"></td>
							<td id = "wed1430"></td>
							<td id = "thu1430"></td>
							<td id = "fri1430"></td>
						</tr>
						<tr>
							<td><strong>15:00</strong></td>
							<td id = "mon1500"></td>
							<td id = "tue1500"></td>
							<td id = "wed1500"></td>
							<td id = "thu1500"></td>
							<td id = "fri1500"></td>
						</tr>
						<tr>
							<td><strong>15:30</strong></td>
							<td id = "mon1530"></td>
							<td id = "tue1530"></td>
							<td id = "wed1530"></td>
							<td id = "thu1530"></td>
							<td id = "fri1530"></td>
						</tr>
						<tr>
							<td><strong>16:00</strong></td>
							<td id = "mon1600"></td>
							<td id = "tue1600"></td>
							<td id = "wed1600"></td>
							<td id = "thu1600"></td>
							<td id = "fri1600"></td>
						</tr>
						<tr>
							<td><strong>16:30</strong></td>
							<td id = "mon1630"></td>
							<td id = "tue1630"></td>
							<td id = "wed1630"></td>
							<td id = "thu1630"></td>
							<td id = "fri1630"></td>
						</tr>
						<tr>
							<td><strong>17:00</strong></td>
							<td id = "mon1700"></td>
							<td id = "tue1700"></td>
							<td id = "wed1700"></td>
							<td id = "thu1700"></td>
							<td id = "fri1700"></td>
						</tr>
						<tr>
							<td><strong>17:30</strong></td>
							<td id = "mon1730"></td>
							<td id = "tue1730"></td>
							<td id = "wed1730"></td>
							<td id = "thu1730"></td>
							<td id = "fri1730"></td>
						</tr>
					</tbody>
				</table>				
			</div>
		</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modaltitle">Request Appointment</h4>
      </div>
	  <form action="confirm.php" method="post">
          <div class="modal-body">
              <table>
                  <tr><td>Professor:</td><td><?=$profName?></td></tr>
                  <tr><td>Student:</td><td><?=$_SESSION['realname']?></td></tr>
                  <tr><td>Course:</td><td><input type="text" name="course"></td></tr>
                  <tr><td>Date:</td><td id="date"></td></tr>
              </table>
                <p>Notes:</p><textarea rows="10" cols="50" name="notes" value="notes"></textarea>
          </div>

          <div class="modal-footer">
            <!--<button type="button" class="btn btn-primary" onClick="confirmRequest()">Send Appointment Request</button>-->
            <input type="submit" class="btn btn-primary" value="submit">
            <input type="hidden" id="appt" name="appt" value="<script>selected</script>">
            <input type="hidden" id="stud" name="stud" value="<?=$stud?>">
            <input type="hidden" id="prof" name="prof" value="<?=$prof?>">
            <input type="hidden" id="profname" name="profname" value="<?=$profName?>">
            <input type="hidden" id="day" name="day" value="">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </form>
    </div>

  </div>
</div>

<p id="demo"></p>
<?php
}
else {
    echo "<h1>No schedule for $profName</h1>
    
    <p>$profName hasn't submitted his or her schedule yet. Please encourage him or her to submit a schedule so that students can book appointments.</p>";
}

include 'footer.php';
?>
</body>
</html>