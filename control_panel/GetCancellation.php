<!DOCTYPE HTML>
<html>
<h1>Cancellation Submitted.</h1>

<body>

<?php 
$ID = $_GET['ID'];
?>
<br>
<?php
$f = "requests.txt";
$all=file_get_contents($f);
$lines=explode("\n", $all);
$n=count($lines)-2;
for ($ln=0; $ln <= $n; $ln++) {
	if(strpos($lines[$ln], $ID) !== false) {
		$RL = $ln;
		break;
	}
}
if (isset($RL) == false) {
	echo "No Job ".$ID."found.";
	exit();
}

if (strpos($lines[$RL], "submitted") !== false) {
    echo "Job ".$ID." already submitted, cannot cancel.";
    exit();
}


$rewrite = fopen($f, 'w+');
for ($LN=0; $LN <=$n; $LN++) {
	if ($LN == $RL) {
		$newline = $ID." CANCELLED ! \n";
		fwrite($rewrite, $newline);
	} else {
		fwrite($rewrite, $lines[$LN]."\n");
	}
}
fclose($rewrite);
	
?>
<br><br>
Requests have been updated. <br>
Back to <a id="home" href="/control_panel/control_panel.php">Control Panel</a>
 
</body>
</html>
