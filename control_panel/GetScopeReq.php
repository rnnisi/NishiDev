<!DOCTYPE HTML>
<html>
<h1>Request has been sent</h1>

<body>

<?php 
$nickname = $_POST['nickname'];
$RunTime = $_POST['RunTime'];
$trigger = $_POST['trigger'];
$channels = $_POST['channels'];
print_r($_POST);
echo $nickname;
?>
<br><br>
<?php
$f = "requests.txt";
if (file_exists($f) == false) {
	$l = 0;
} else {
	$l = count(file($f));
}
echo "ID: Req_".strval($l);
$Request = "Req_".strval($l)."; ".$nickname."; ".$RunTime."; ".$trigger."; ".$channels."; ! \n";
$fh = fopen($f, 'a+');
fwrite($fh, $Request);
fclose($fh);
?>
<br><br>
Back to <a id="home" href="/control_panel/control_panel.php">Control Panel</a> 
</body>
</html>
