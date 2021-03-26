<!DOCTYPE HTML>
<html>
<h1>Request has been sent</h1>

<body>

<?php 
$specs=$_POST['specs'];
$ID=$_POST['ID'];

?>
<br>
<?php
$f = "requests.txt";
if (file_exists($f) == false) {
	$l = 0;
} else {
	$l = count(file($f));
}
echo "ID: Req_".strval($l)."<br>";
echo "New Request sent.<br>";
// process function generature requests seperately 
$NewLine="Req_".strval($l)." ; ".$specs." $ \n";
$fh = fopen($f, 'a+');
fwrite($fh, $NewLine);
fclose($fh);


?>
<br><br>
Back to <a id="home" href="/control_panel/control_panel.php">Control Panel</a> 
</body>
</html>
