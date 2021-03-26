<!DOCTYPE HTML>
<html>
<h1>Device Removal Submitted.</h1>

<body>

<?php 
$ID = $_GET['dev'];
?>
<br>
<?php
$f = "DeviceList.txt";
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
	echo "No device ".$ID."found.";
	exit();
}


$rewrite = fopen($f, 'w+');
for ($LN=0; $LN <=$n; $LN++) {
	if ($LN == $RL) {
	} else {
		fwrite($rewrite, $lines[$LN]."\n");
	}
}
fclose($rewrite);
	
?>
<br><br>
Device List has been updated. <br>
Back to <a id="home" href="/control_panel/control_panel.php">Control Panel</a>
 
</body>
</html>
