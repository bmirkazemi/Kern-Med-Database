<?PHP
require('connect.php');
$q = $_POST['rep1'];

$sqlVitals = "SELECT * FROM cases NATURAL JOIN patient WHERE cid = '{$q}'";
$sqlDiagnosis = "SELECT * FROM patient NATURAL JOIN cases INNER JOIN diagnosis ON diagnosis.did = cases.did WHERE cases.cid = '{$q}'";
$sqlRN = "SELECT fname, lname, licno FROM employee NATURAL JOIN cases WHERE cid = '{$q}'";
$sqlMD = "SELECT * FROM employee NATURAL JOIN diagnosis INNER JOIN cases ON diagnosis.did = cases.did WHERE cases.cid = '{$q}'";

$resVitals = pg_query($db, $sqlVitals);
$resDiagnosis = pg_query($db, $sqlDiagnosis);
$resRN = pg_query($db, $sqlRN);
$resMD = pg_query($db, $sqlMD);

while ($row = pg_fetch_assoc($resVitals)) {
	$pid = $row['pid'];
	$fname = $row['fname'];
	$lname = $row['lname'];
	$mname = $row['mname'];
	$street = $row['street'];
	$city = $row['city'];
	$state = $row['state'];
	$zip = $row['zip'];
	$phone = $row['phone'];
	$dob = $row['dob'];
	$sex = $row['sex'];
	$ins = $row['instype'];
	$lang = $row['language'];
	$bpsys = $row['bpsys'];
	$bpdia = $row['bpdia'];
	$hrate = $row['hrate'];
	$rrate = $row['rrate'];
	$vdate = $row['vdate'];
	$cid = $row['cid'];
}

while ($row = pg_fetch_assoc($resDiagnosis)) {
	$diagnosis = $row['diagnosis'];
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

$sqlLab = "SELECT * FROM diagnosis FULL OUTER JOIN labs ON diagnosis.lid = labs.labid WHERE diagnosis.did = '{$did}'";
$resLab = pg_query($db, $sqlLab);

while ($row = pg_fetch_assoc($resLab)) {
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

require_once('tcpdf/tcpdf.php');

$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Kern Medical Hospital');
$pdf->SetTitle('Vitals Report');
$pdf->SetSubject('Vitals Report');
$pdf->SetKeywords('patient, report, case, vitals, kernmed, hospital');

$pdf->SetHeaderData("img/kernmed.jpg", 80, "Kern Medical Patient Report", "Report Generated ".date('m-d-Y H:i:s')."\nPatient: ".$fname." ".$lname." | Patient ID: ".$pid." ");

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
 	require_once(dirname(__FILE__).'/lang/eng.php');
  	$pdf->setLanguageArray($l);
}

$pdf->AddPage();

$html = '
	<h3>CASE #: '.str_pad($cid, 3, "0", STR_PAD_LEFT).'</h3>
	<br>
	<h5>Date Of Visit: '.$vdate.'
	<br>
	Nurse: '.$RNlname.', '.$RNfname.'
	<br>
	License: '.$RNlicno.'
	<br>
	<br>
	Blood Pressure: '.$bpsys.'/'.$bpdia.'
	<br>
	Heart Rate: '.$hrate.' (beats per minute)
	<br>
	Resipratory Rate: '.$rrate.' (breaths per minute)
	<br>
	<br>
	<center>
	<h4>Lab Results</h4>
	<br>
	<table border="" cellpadding="2" cellspacing="2">
	<thead>
	<tr style="background-color:gray;color:black;">
		<td width="200" align="center"><b>Lab</b></td>
		<td width="200" align="center"><b>Value</b></td>
	</tr>
	<tr style="background-color:white;color:black;">
		<td width="200" align="center"><b>Blood Urea Nitrogen</b></td>
		<td width="200" align="center"><b>'.$bun.'</b></td>
	</tr>
	<tr style="background-color:lightgray;color:black;">
		<td width="200" align="center"><b>Calcium</b></td>
		<td width="200" align="center"><b>'.$calcium.'</b></td>
	</tr>
	<tr style="background-color:white;color:black;">
		<td width="200" align="center"><b>Carbon Dioxide</b></td>
		<td width="200" align="center"><b>'.$c02.'</b></td>
	</tr>
	<tr style="background-color:lightgray;color:black;">
		<td width="200" align="center"><b>Chloride</b></td>
		<td width="200" align="center"><b>'.$chloride.'</b></td>
	</tr>
	<tr style="background-color:white;color:black;">
		<td width="200" align="center"><b>Creatinine</b></td>
		<td width="200" align="center"><b>'.$creatinine.'</b></td>
	</tr>
	<tr style="background-color:lightgray;color:black;">
		<td width="200" align="center"><b>Glucose</b></td>
		<td width="200" align="center"><b>'.$glucose.'</b></td>
	</tr>
	<tr style="background-color:white;color:black;">
		<td width="200" align="center"><b>Potassium</b></td>
		<td width="200" align="center"><b>'.$potassium.'</b></td>
	</tr>
	<tr style="background-color:lightgray;color:black;">
		<td width="200" align="center"><b>Sodium</b></td>
		<td width="200" align="center"><b>'.$sodium.'</b></td>
	</tr>
	</table>
	<br>
	<br>
	Doctor: '.$MDlname.', '.$MDfname.'
	<br>
	License: '.$MDlicno.'
	<br>
	Diagnosis: '.$diagnosis.'.
	<br>
	<h4>Prescriptions</h4>
	<br>
	<table border="" cellpadding="2" cellspacing="2">
	<thead>
	<tr style="background-color:gray;color:black;">
		<td width="100" align="center"><b>Medication</b></td>
		<td width="100" align="center"><b>Dosage</b></td>
		<td width="100" align="center"><b>Frequency</b></td>
		<td width="100" align="center"><b>Start Date</b></td>
		<td width="100" align="center"><b>End Date</b></td>
	</tr>'/*;
for ($i = 0; $i < $countRX; $i++) {
	$html ='
	<tr style="background-color:gray;color:black;">
		<td width="120" align="center"><b>'.$med[$i].'</b></td>
		<td width="120" align="center"><b>'.$dosage[$i].'</b></td>
		<td width="120" align="center"><b>'.$freq[$i].'</b></td>
		<td width="120" align="center"><b>'.$sdate[$i].'</b></td>
		<td width="120" align="center"><b>'.$edate[$i].'</b></td>
	</tr>'
}
$html ='
	</table>
	<br>
	'*/;

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output('report.pdf', 'I');

//done
