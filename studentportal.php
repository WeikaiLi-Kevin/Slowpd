<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<meta content="" name="description">
	<meta content="" name="author">
	
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
<title>Appointment Scheduler</title>
<link href="css/business-frontpage.css" rel="stylesheet">
<style>
	td {
   height: 51px;
}
</style>
	
<script>	
function popupModal(e){
			var parent_id = $(e).parent().attr('id');
			console.log(parent_id);
			document.getElementById("appt").value=parent_id;
			document.getElementById("modaltitle").innerHTML= "Request Appointment: " + parent_id;
			$('#myModal').modal('show'); 
}	
	
//function confirmRequest(){
//	window.location="confirm.php";
//}

	$(document).ready(function(){

		//Testing
	<?php $week = file_get_contents('week.json'); ?>
	var week = JSON.parse('<?php echo $week?>');
	var monday = week.monday;
	var tuesday = week.tuesday;
	var wednesday = week.wednesday;
	var thursday = week.thursday;	
	var friday = week.friday;
	var daysoftheweek = [monday,tuesday,wednesday,thursday,friday];

	
daysoftheweek.forEach(function(element) {
		for (var key in element) {
			if (element.hasOwnProperty(key)) {
				if(element[key]==""){
				//document.getElementById(key).innerHTML = '<button type="button" class="btn-sm btn-primary btncheck" data-toggle="modal" data-target="#myModal">Request Appt</button>';
				document.getElementById(key).innerHTML = '<button type="button" class="btn-sm btn-primary btncheck" onClick="popupModal(this)">Request Appt</button>';				
				}else if(element[key]=="pending"){
				document.getElementById(key).innerHTML = '<strong style="color: green">Pending</strong>';			
				}else if(element[key]["confirmed"]=="no"){
					document.getElementById(key).innerHTML = '<strong style="color: green">PENDING</strong>';	
				}
				else{
				document.getElementById(key).innerHTML = '<strong style="color: maroon">Unavailable</strong>';
				//document.getElementById(key).innerHTML = element[key]["course"];
				}
			}
		}
	});
});
	</script>

</head>
<body>
	<!-- Navigation -->
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button class="navbar-toggle" data-target="#bs-example-navbar-collapse-1" data-toggle="collapse" type="button"><span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button> <a class="navbar-brand" id="rulelist">Teacher Scheduler</a>
			</div><!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li>
						<a id="standings">Search</a>
					</li>					
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="#" id="nhlsched">Logoff</a>
					</li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container -->
	</nav><!-- Image Background Page Header -->
	<div class="container">
		<div class="standings col-sm-12 well">
			<div align="center" class="col-sm-12">
				<h1>Schedule</h1>
			</div>
			<div>			
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
      <div class="modal-body">
	  <form action="confirm.php" method="post">
	  <table>
	  <tr><td>Professor:</td><td><input type="text" name="professor"></td></tr>
	  <tr><td>Course:</td><td><input type="text" name="course"></td></tr>
	  </table>
		<p>Notes:</p><textarea rows="10" cols="50" name="notes" value="notes""></textarea>
      </div>
	  
      <div class="modal-footer">
	    <!--<button type="button" class="btn btn-primary" onClick="confirmRequest()">Send Appointment Request</button>-->
		<input type="submit" class="btn btn-primary"></button>
		<input type="hidden" id = "appt" name="appt" value="<script>selected</script>">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</form>
      </div>
    </div>

  </div>
</div>
<?php echo "grog"; ?>
<p id="demo"></p>
</body>
</html>