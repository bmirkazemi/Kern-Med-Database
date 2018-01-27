<?php
require('connect.php');
require('navbars/rn_nav.php');

$eid = 8;
$pid;

if(isset($_GET['q']) && $_GET['q'] != '') {
	//sanitization
	$q = $_GET['q'];
	$sqlDIAGNOSIS = "SELECT * FROM patient NATURAL JOIN cases INNER JOIN diagnosis ON diagnosis.did = cases.did WHERE cases.cid = {$q}";
	$sqlRN = "SELECT fname, lname, licno FROM employee NATURAL JOIN cases WHERE cid = '{$q}'";
	$sqlMD = "SELECT * FROM employee NATURAL JOIN diagnosis INNER JOIN cases ON diagnosis.did = cases.did WHERE cases.cid = '{$q}'";

	$resDIAGNOSIS = pg_query($db, $sqlDIAGNOSIS);
	$resRN = pg_query($db, $sqlRN);
	$resMD = pg_query($db, $sqlMD);
	
	while ($row = pg_fetch_assoc($resDIAGNOSIS)) {
		$diag = $row['diagnosis'];
		$did = $row['did'];
	}
	
	while ($row = pg_fetch_assoc($resRN)) {
		$RNfname = $row['fname'];
		$RNlname = $row['lname'];
		$RNlicno = $row['licno'];
	}
	
	while ($row = pg_fetch_assoc($resMD)) {
		$MDfname = $row['fname'];
		$MDlname = $row['lname'];
		$MDlicno = $row['licno'];
	}

	$sqlLAB = "SELECT * FROM diagnosis FULL OUTER JOIN labs ON diagnosis.lid = labs.labid WHERE diagnosis.did = '{$did}'";
	$resLAB = pg_query($db, $sqlLAB);

	while ($row = pg_fetch_assoc($resLAB)) {
		$bun = $row['bun'];
		$calcium = $row['calcium'];
		$c02 = $row['c02'];
		$chloride = $row['chloride'];
		$creatinine = $row['creatinine'];
		$glucose = $row['glucose'];
		$potassium = $row['potassium'];
		$sodium = $row['sodium'];
	}

	$sqlRX = "SELECT * FROM prescription INNER JOIN prescribes ON prescribes.prid = prescription.preid WHERE prescribes.did = '{$did}'";
	$resRX = pg_query($db, $sqlRX);
	$countRX = pg_num_rows($resRX);

	$med = array();	
	$dosage = array();	
	$freq = array();	
	$sdate = array();	
	$edate = array();	

	while ($row = pg_fetch_assoc($resRX)) {
		$med[] = $row['medication'];
		$dosage[] = $row['dosage'];
		$freq[] = $row['frequency'];
		$sdate[] = $row['sdate'];
		$edate[] = $row['edate'];
	}


	$sqlCase = "SELECT * FROM cases NATURAL JOIN patient WHERE cid = {$q}";
	if ($res = pg_query($db, $sqlCase)) {
		while($row = pg_fetch_assoc($res)) {
			$pid = $row['pid'];
			$sqlHistory = "SELECT * FROM cases WHERE pid = {$pid} ORDER BY vdate DESC";
?>

<div class="welcome">
	<h2><?php echo $row['fname']." ".$row['lname']." #".str_pad($row['cid'],3,"0",STR_PAD_LEFT); ?></h2>
</div>
<div class="viewInfo">
	<a data-toggle="modal" href="#myModal2" class="btn btn-success btn-lg">View Info</a>
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

<div class="col-lg-9">
	<div class="panel panel-success" data-effect="helix">
	<div class="panel-heading">Patient Overview</div>
		<div class="accordion" id="accordion2">
        	<div class="accordion-group accordian-info">
            	<div class="accordion-heading">
                	<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                	<center><em class="fa fa-minus fa-fw"></em>Prior Visists</center>
                	</a>
            	</div>
            	<div class="panel-body">
                	<div id="collapseTwo" class="accordion-body collapse in">
                    	<div class="accordion-inner">
							<div class="visits">
								<center>
								<h1>HELLO</h1>
								</center>
							</div>
						</div>
                	</div>
            	</div>
        	</div>
    	</div>
	</div>
</div>

<BR>
<BR>
<HR>
<BR>

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
	}
}
?>
