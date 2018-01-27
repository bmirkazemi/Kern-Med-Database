<?php
require('connect.php');
require('navbars/md_nav.php');

$eid = 10;
$status = 1;
$doctor = "SELECT * FROM employee WHERE eid = '{$eid}'";
$patient = "SELECT * FROM patient INNER JOIN cases ON patient.pid=cases.pid INNER JOIN diagnosis ON diagnosis.did=cases.did WHERE diagnosis.status=1 AND diagnosis.eid = '{$eid}'";
$status = "SELECT * FROM patient NATURAL JOIN cases INNER JOIN diagnosis ON diagnosis.did = cases.did WHERE diagnosis.status = '{$status}'";

$resStatus = pg_query($db, $status);
$resDoctor = pg_query($db, $doctor);
$resPatient = pg_query($db, $patient);

while($row = pg_fetch_assoc($resDoctor)) {
?>

<div class="welcome">
	<h2> Welcome <?php echo $row['fname']." ".$row['lname']; ?></h2>
	<h2> <?php echo $row['lictype'].": #".$row['licno'];?></h2>
</div>
<div class="viewInfo">
	<a data-toggle="modal" href="#myModal2" class="btn btn-danger btn-lg">View Info</a>
</div>

<!--MODAL FOR PATIENT INFORMATION-->
<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              	<center><h4 class="modal-title" id="myModalLabel"><b>Doctor Information</b></h4></center>
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
				<b><h3>License Type: </b> 
				<?php echo $row['lictype']; ?>
				<br>												
				<b><h3>License No: </b> 
				<?php echo $row['licno']; ?>
				<br>
				<b><h3>Currently Employed? : </b> 
				<?php if($row['edate'] == NULL) echo "YES"; else echo "NO"; ?>
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
<div class="col-sm-6" style="float:left;">
	<div class="panel panel-danger" data-effect="helix">
	<div class="panel-heading">Patients awaiting Diagnosis <span class="badge">9</span></div>
						<center>
						<table class="table table-hover" data-effect="fade">
              			<thead>
                			<tr>
				  				<th>Name</th>
				  				<th>Date of Birth</th>
				  				<th>Assessment</th>
							</tr>
			  			</thead>
						<tbody>
<?PHP 
while ($row = pg_fetch_assoc($resStatus)) {
	echo "<tr onclick =\"window.location='diag.php?q=".$row['did']."';\">";
	echo "<td>".$row['lname'].", ".$row['fname']."</td>";
	echo "<td>".$row['dob']."</td>";
	echo "<td>".$row['diagnosis']."</td>";
}
?>						
              			</tbody>
            			</table>
						</center>

	</div>
</div>

<div class="col-sm-6" style="float:right;">
<div class="panel panel-danger" data-effect="helix">
	<div class="panel-heading"></div> 
		<div class="accordion" id="accordion2">
		<div class="accordion-group accordian-info">
        	<div class="accordion-heading">
              	<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                <em class="fa fa-minus fa-fw"></em>Past Patients
              	</a>
			</div>
			<div class="panel-body">
            	<div id="collapseOne" class="accordion-body collapse">
              		<div class="accordion-inner">
						<center>
						<table class="table table-hover" data-effect="fade">
              			<thead>
                			<tr>
								<th>Date</th>
				  				<th>Name</th>
				  				<th>Date of Birth</th>
				  				<th>Diagnosis</th>
							</tr>
			  			</thead>
						<tbody>
<?PHP 
while ($row = pg_fetch_assoc($resPatient)) {
	echo "<tr onclick =\"window.location='past_diag.php?q=".$row['cid']."';\">";
	echo "<td>".$row['vdate']."</td>";
	echo "<td>".$row['fname'].", ".$row['lname']."</td>";
	echo "<td>".$row['dob']."</td>";
	echo "<td>".$row['diagnosis']."</td>";
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
				<a href='index.php' class='btn btn-success' type='button'>Yes, Logout</a>
			</div>
      	</div>
    </div>
</div>

</body>
<HTML>
