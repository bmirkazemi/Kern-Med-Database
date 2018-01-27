<?php
require('connect.php');
require('navbars/rn_nav.php');

$eid = 8;
$status = 0;

$sqlDrs = "SELECT eid, fname, lname FROM employee WHERE lictype = 'MD' AND edate IS NULL ORDER BY lname ASC";
$resDrs = pg_query($db, $sqlDrs);
if(!$resDrs) {
	echo "error with Dr's";
	exit;
}

if((!empty($_POST['case'])) && (!empty($_POST['date'])) && (!empty($_POST['bpsys'])) && (!empty($_POST['bpdia'])) && (!empty($_POST['rrate'])) && (!empty($_POST['hrate'])) && (!empty($_POST['drlist']))) {
	$sqlSubmit = "UPDATE cases 
	SET vdate = '{$_POST['date']}'::date, bpsys = {$_POST['bpsys']}, bpdia = {$_POST['bpdia']}, rrate = {$_POST['rrate']}, hrate = {$_POST['hrate']}, eid = {$eid}
		WHERE cid = {$_POST['case']}";
	if($resSubmit = pg_query($db, $sqlSubmit)) {
		//echo "successful submit";
		echo $result;
	}
	$sqlUpdate = "UPDATE diagnosis 
		SET eid = {$_POST['drlist']}, status = 1
		FROM cases c
		WHERE c.cid = {$_POST['case']} AND c.did = diagnosis.did";
	if($resUpdate = pg_query($db, $sqlUpdate)){
		header("Location: nurse.php");
		//echo "successful update";
	}
}

//$nurse = "SELECT * FROM employee WHERE eid = '{$eid}'";
//$resNurse = pg_query($db, $nurse);

if(isset($_GET['q']) && $_GET['q'] != '') {
	//sanitization
	$q = $_GET['q'];
	$currentPatients = "SELECT * FROM cases NATURAL JOIN patient where cid = {$q}";
	if($resCurr = pg_query($db, $currentPatients)) {

while($row = pg_fetch_assoc($resCurr)) {
?>

<div class="welcome">
	<h2><b> Patient Name: </b><?php echo $row['fname']." ".$row['lname']; ?></h2>
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
<div class="col-lg-8">
	<div class="panel panel-success" data-effect="helix">
	<div class="panel-heading"><center>Initial Assessment</center></div>
		<form id = "cs" method = "POST" name = "cs">
			<br>
			<label for="case" class="control-label">Case #: </label> <input type = "text" id = 'case' name = 'case' value = "<?php echo str_pad($row['cid'],3,"0", STR_PAD_LEFT); ?>" readonly/><br><br>	
			<label for="date" class="control-label">Visit Date: </label> <input type = "date" id = 'date' name = 'date' value = "<?php echo date('m-d-Y'); ?>"/><br><br> 
			<label for "bpsys" class="control-label">Blood Pressure: </label> <input type="number" placeholder='systolic'id='bpsys' name='bpsys' min='0' step='1'/>
			/ <input type="number" placeholder='diastolic' id='bpdia' name='bpdia' min='0' step='1'/><br><br>
			<label for "hrate" class="control-label">Heart Rate: </label> <input type="number" id='hrate' name='hrate' min='0' step='1'/><br><br>
			<label for "rrate" class="control-label">Respiratory Rate: </label> <input type="number" id='rrate' name='rrate' min='0' step='1'/><br><br>
	</div>
</div>

<?PHP 
}
?>
<center>

<div class="col-lg-4">
	<div class="panel panel-success" data-effect="helix">
	<div class="panel-heading">Doctor Selection</div>
		<br>
		<form name = "drlist">
		<select name = "drlist" id = "drlist" size=15>
<?PHP
					  while ( $row = pg_fetch_assoc($resDrs)) {
							echo "<option id = ".$row['eid']." value=".$row['eid'].">".$row['lname']." , ".$row['fname']."</option>";
					  }
?>
		</select>
	</div>
</div>

<br>
<center>

<div class="col-lg-12">
	<div class="panel panel-success" data-effect="helix">
	<div class="panel-heading">Submissions</div>
		<br>
		<button type="submit" class="btn btn-danger"><b>Complete</b></button>
		</form>
		<br><br>
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
}
?>
