<!DOCTYPE HTML>
<html>
<h1>Registered Pi List</h1>

<body>

<br>
<?php
$f = "DeviceList.txt";
$all=file_get_contents($f);
$lines=explode(" ! \n", $all);
$n=count($lines)-2;
?>
<?php
for ($LN=0; $LN <=$n; $LN++) {
	$vals=explode(":", $lines[$LN]);
	$name=$vals[0];
	$txt=$vals[1];
	echo "<b>".$name."</b><br>";
	echo $vals[1]."<br>";
	$addy = $name.".local/".$name."/";
	$link = '<A id="'.$name.'" target="_blank" href="//'.$addy.'">Data</A><br><br>';
	echo $link;
	unset($name, $vals, $txt, $IP);
}
?>

</body>
</html>
