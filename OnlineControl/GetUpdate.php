<!DOCTYPE HTML>
<html>
<h1>Update Submitted.</h1>

<body>

<?php 
$REQN = $_GET['ReqN'];
$stat = $_GET['stat'];
?>
<br>
<?php
$f = "requests.txt";
$all=file_get_contents($f);
$lines=explode("\n", $all);
$n=count($lines)-2;
for ($ln=0; $ln <= $n; $ln++) {
	echo nl2br($lines[$ln]);
	echo "lines ".$lines[$ln];
	if(strpos($lines[$ln], $REQN) !== false) {
		$RL = $ln;
		break;
	}
}
if (isset($RL) == false) {
	echo "Request does not exist";
	exit();
}



$rewrite = fopen($f, 'w+');
for ($LN=0; $LN <=$n; $LN++) {
	if ($LN == $RL) {
		$newline = str_replace(" $ " ," ! Status: ".$stat." $ \n", $lines[$RL]);
		echo $newline;
		fwrite($rewrite, $newline);
	} else {
		fwrite($rewrite, $lines[$LN]);
	}
}
fclose($rewrite);
	
?>
<br><br>
Requests have been updated. <br>
<a id="home" href="/control_panel">Home</a><br>
<a id="requests" href="/control_panel/requests.txt"><Jobs></a>
	 
</body>
</html>
