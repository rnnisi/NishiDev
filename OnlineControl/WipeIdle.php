<!DOCTYPE HTML>
<html>
<h1>Idle Reset done.</h1>

<body>

<br>
<?php
$f = "requests.txt";
$all=file_get_contents($f);
$lines=explode("\n", $all);
$n=count($lines)-2;
for ($ln=0; $ln <= $n; $ln++) {
	if(strpos($lines[$ln], "IDLE") !== false) {
		$RL = $ln;
		break;
	}
}
if (isset($RL) == false) {
	echo "No Idle command found.";
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
Queue has been updated. <br>
Back to <a id="home" href="/control_panel/control_panel.php">Control Panel</a>
 
</body>
</html>
