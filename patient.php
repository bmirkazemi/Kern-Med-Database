<?php
require('connect.php');
require('navbars/patient_nav.php');

$pid = 10;
$patient = "SELECT * FROM patient WHERE pid = '{$pid}'";
$case = "SELECT * FROM cases INNER JOIN diagnosis ON diagnosis.did = cases.did WHERE pid = '{$pid}' ORDER BY vdate DESC";
$rx = "SELECT * FROM prescription INNER JOIN prescribes ON prescription.preid=prescribes.prid 
								  INNER JOIN  diagnosis ON diagnosis.did = prescribes.did 
								  INNER JOIN cases ON diagnosis.did=cases.did WHERE pid = '{$pid}'";

$resPatient = pg_query($db, $patient);
$resCase = pg_query($db, $case);
$resRX = pg_query($db, $rx);

if(!$resRX) {
	echo "error prescription";
	exit;
}
if(!$resPatient) {
	echo "error patient";
	exit;
}
if(!$resCase) {
	echo "error case";
	exit;
}

while($row = pg_fetch_assoc($resPatient)) {
?>

<div class="welcome">
	<h2> Welcome <?php if($row['sex'] == 'Female') { echo "Mrs. "; } else { echo "Mr. "; } echo $row['lname']; ?></h2>
</div>
<div class="viewInfo">
	<a data-toggle="modal" href="#myModal2" class="btn btn-info btn-lg">View Info</a>
</div>

<!--MODAL FOR PATIENT INFORMATION-->
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              	<center><h4 class="modal-title" id="myModalLabel"><b>Patient Information</b></h4></center>
          	</div>
            <div class="modal-body">
				<b><h3>Full Name: </b>
				<?php echo $row['fname']." "; 
					  echo $row['mname']." "; 
					  echo $row['lname']." ";?>
				<br>
				<b><h3>Address: </b> 
				<?php echo $row['street']; ?>
				<br>
				<b><h3>City: </b>
				<?php echo $row['city']." "; 
					  echo $row['state']." "; 
					  echo $row['zip']." "; ?>
				<br>
				<b><h3>Phone: </b> 
				<?php echo $row['phone']; ?>
				<br>
				<b><h3>Birthday: :</b> 
				<?php echo $row['dob']; ?>
				<br>						
				<b><h3>Sex: </b> 
				<?php echo $row['sex']; ?>
				<br>												
				<b><h3>Insurance: </b> 
				<?php echo $row['instype']; ?>
				<br>												
				<b><h3>Primary Langauge: </b> 
				<?php echo $row['language']; ?>
				<br>
				<br>
       		</div>
          	<div class="modal-footer">
             	<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
         	</div>
  		</div>
  	</div>
</div>
<?PHP 
}
?>
<hr>
<center>
<div class="col-lg-6">
<div class="panel panel-info" data-effect="helix">
	<div class="panel-heading"></div> 
		<div class="accordion" id="accordion2">
		<div class="accordion-group accordian-info">
        	<div class="accordion-heading">
              	<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                <em class="fa fa-minus fa-fw"></em>Recent Visits
              	</a>
			</div>
			<div class="panel-body">
            	<div id="collapseOne" class="accordion-body collapse in">
              		<div class="accordion-inner">
						<center>
						<table class="table table-hover" data-effect="fade">
              			<thead>
                			<tr>
								<th>Date</th>
				  				<th>Blood Pressure</th>
				  				<th>Heart Rate</th>
				  				<th>Diagnosis</th>
							</tr>
			  			</thead>
						<tbody>
<?PHP 
while ($row = pg_fetch_assoc($resCase)) {
	echo "<tr onclick =\"window.location='index.html';\">";
	echo "<td>".$row['vdate']."</td>";
	echo "<td>".$row['bpsys']." / ".$row['bpdia']."</td>";
	echo "<td>".$row['hrate']."</td>";
	echo "<td>".$row['diagnosis']."</td></tr>";
}
?>						
              			</tbody>
            			</table>
						</center>
			  		</div>
				</div>
            </div>
		</div>
	</div>
</div>
<br>
<div class="panel panel-info" data-effect="helix">
	<div class="panel-heading"></div> 
		<div class="accordion" id="accordion2">
		<div class="accordion-group accordian-info">
        	<div class="accordion-heading">
              	<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                <em class="fa fa-minus fa-fw"></em>My Prescriptions
              	</a>
			</div>
			<div class="panel-body">
            	<div id="collapseTwo" class="accordion-body collapse">
              		<div class="accordion-inner">
						<center>
						<table class="table table-hover" data-effect="fade">
              			<thead>
                			<tr>
				  				<th>Date</th>
				  				<th>Medication</th>
								<th>Dosage</th>
								<th>Frequency</th>
							</tr>
			  			</thead>
						<tbody>
<?PHP 
while ($row = pg_fetch_assoc($resRX)) {
	echo "<tr onclick =\"window.location='index.html';\">";
	echo "<td>".$row['sdate']."</td>";
	echo "<td>".$row['medication']."</td>";
	echo "<td>".$row['dosage']." mg</td>";
	echo "<td>".$row['frequency']."</td>";
}
?>						
              			</tbody>
            			</table>
						</center>
			  		</div>
				</div>
            </div>
		</div>
	</div>
</div>
</div>


<!--MODAL FOR LOGOUT-->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
     	<div class="modal-content">
         	<div class="modal-header">
              	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel"></h4>
			</div>
            <div class="modal-body">
              	<h4>Are you sure you want to logout?</h4>
          	</div>
            <div class="modal-footer">
              	<button type="button" class="btn btn-danger" data-dismiss="modal"><b>Cancel</b></button>
              	<a href='index.php' class="btn btn-success" role="button"><b>Yes, Logout</b></a>
           	</div>
      	</div>
    </div>
</div>

</body>
<HTML>
