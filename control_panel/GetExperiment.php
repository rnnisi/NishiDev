<!DOCTYPE HTML>
<html>
<h1>Request has been sent</h1>

<body>

<?php 
$scopeName = $_POST['ScopeName'];
$scopeRunTime = $_POST['DAQRunTime'];
$scopeTrigger = $_POST['ScopeTrigger'];
$scopeChan = $_POST['ScopeChan'];
$numVals = count($_POST);
$keys = array_keys($_POST);
#echo "_POST:".print_r($_POST);	#print all the post variables 
echo "New Job: '<b>". $scopeName . "</b>' will read data from <b> channel(s) ". $scopeChan . " </b> for <b> " . $scopeRunTime . " seconds</b> with trigger set to <b>" . $scopeTrigger."</b>.";
?>
<br><br>
<?php
$f = "requests.txt";
if (file_exists($f) == false) {
	$l = 0;
} else {
	$l = count(file($f));
}
echo "ID: Req_".strval($l)."<br>";
$ScopeRequest = "Req_".strval($l)." ; ".$scopeName."; ".$scopeRunTime."; ".$scopeTrigger."; ".$scopeChan."; ! ";

// process function generature requests seperately 

function checkRemainder($val, $div) {
	$remainder = ($val % $div);
	if ($remainder == 0) {
		return "true"; }
}

$sort=1;
$fgProgram = " & ; ";
for ($i = 5; $i<=$numVals-1; $i++) {
	$fgProgram = $fgProgram.$_POST[$keys[$i]];
	if (checkRemainder(($i-4), 3)  == "true") {
		if ($i==($numVals-1)) { $fgProgram=$fgProgram.", ! "; }
		else {$fgProgram = $fgProgram." & "; }
	} else { 
		$fgProgram = $fgProgram."; "; }
	$sort=$sort + 1;
}
$NewLine=$ScopeRequest.$fgProgram."\n";
$fh = fopen($f, 'a+');
fwrite($fh, $NewLine);
fclose($fh);


?>
<br><br>
Back to <a id="home" href="/control_panel/control_panel.php">Control Panel</a> 
</body>
</html>
