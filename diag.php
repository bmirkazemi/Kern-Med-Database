<?php
require('connect.php');
require('navbars/md_nav.php');

$doctor = "SELECT fname, lname, licno FROM employee WHERE eid = '{$eid}'";
$resMD = pg_query($db, $doctor);

while ($row = pg_fetch_assoc($resMD)) {
	$MDfname = $row['fname'];
	$MDlname = $row['lname'];
	$MDlicno = $row['licno'];
}

if(isset($_GET['q']) && $_GET['q'] != '') {
	//sanitization
	$q = $_GET['q'];
	$sqlRN = "SELECT fname, lname, licno FROM employee NATURAL JOIN cases where cid = '{$q}'";
	$sqlCase = "SELECT * FROM cases NATURAL JOIN patient where cid = '{$q}'";
	if($resCase = pg_query($db, $sqlCase)) {
		while($row = pg_fetch_assoc($resCase)) {
			$pid = $row['pid'];
			$sqlHistory = "SELECT * FROM cases where pid = '{$pid}' ORDER BY vdate DESC";
		

?>

<div class="welcome">
	<h2><b> Patient Name: </b><?php echo $row['fname']." ".$row['lname']; ?></h2>
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
              	<center><h4 class="modal-title" id="myModalLabel"><b>Current Patient Info</b></h4></center>
          	</div>
            <div class="modal-body">
				<b><h3>Full Name: </b>
				<?php echo $row['fname']." "; 
					  echo $row['mname'].". "; 
					  echo $row['lname']." ";?>
				<br>			
				<b><h3>Sex: </b> 
				<?php echo $row['sex']; ?>
				<br>												
				<b><h3>SSN: </b> 
				<?php echo $row['ssn']; ?>
				<br>
				<b><h3>Address: </b> 
				<?php echo $row['street']; ?>
				<br>
				<b><h3>City: </b>
				<?php echo $row['city']." "; 
					  echo $row['state']." "; 
					  echo $row['zip']." "; ?>
				<br>
				<b><h3>Birthday: </b> 
				<?php echo $row['dob']; ?>
				<br>
				<b><h3>Phone: </b> 
				<?php echo $row['phone']; ?>
				<br>			
				<b><h3>Insurance: </b> 
				<?php echo $row['instype']; ?>
				<br>												
				<b><h3>Language: </b> 
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
<hr>
<center>
<?PHP
		}
	}

	
?>
<div class="col-lg-8">
	<div class="panel panel-danger" data-effect="helix">
	<div class="panel-heading">idk</div> 	
		
		<h6>this is where all the information from a case will be updated. Each attribute from the case table will have a input box of some sort with the current value from the corresponding case acting as a placeholder. The nurse will be able to update the case and change the status to 2, ready to see a doctor.</h6>
		<BR><BR><BR><BR>
		<h4>???????</h4>
	
	</div>
</div>

<div class="col-md-4">
<div class="panel panel-danger" data-effect="helix">
	<div class="panel-heading"></div> 
		<div class="accordion" id="accordion2">
		<div class="accordion-group accordian-info">
        	<div class="accordion-heading">
              	<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                <em class="fa fa-minus fa-fw"></em>Other Visits
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
								<th>Case #</th>
							</tr>
						</thead>
						<tbody>					
<?PHP 		
							if ($resHistory = pg_query($db, $sqlHistory)) {
								while ($row2 = pg_fetch_assoc($resHistory)) {
									if ($row2['cid'] != $row['cid']) {
										echo "<tr onclick =\"window.open('past_cases.php?q=".$row2['cid']."', '_blank');\">";
										echo "<td>".$row2['vdate']."</td>";
										echo "<td>".str_pad($row2['cid'], 3, "0", STR_PAD_LEFT)."</td>";
									}
								}
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
				<a href = "index.php" class="btn btn-success" type="button">Yes, Logout</a>
			</div>
      	</div>
    </div>
</div>

<?PHP		
 	
	
}
 ?>
