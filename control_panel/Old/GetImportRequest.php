<!DOCTYPE HTML>
<html>
<h1>Request Imported</h1>

<body>

<?php 
$ID = "Req_".$_POST['ID'];
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
		echo $lines[$ln];
		break;
	}
}
if (isset($RL) == false) {
	echo "No Job ".$ID."found.";
	exit();
}


?>
<br><br>
Requests have been updated. <br>
Back to <a id="home" href="/control_panel/control_panel.php">Control Panel</a>
 
</body>
</html>
