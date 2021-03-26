<!DOCTYPE HTML>
<html>
<h1>Device has been added</h1>
<body>

<?php 
$nickname = $_GET['nickname'];
$txt = $_GET['description'];
?>

<br><br>

<?php
$f = "DeviceList.txt";
echo "Adding ".$Nickname."to known device list.";
$fh = fopen($f, 'a+');
fwrite($fh, strval($nickname.": ".$txt." ! \n"));
fclose($fh);
?>
<br><br>
Back to <a id="home" href="/control_panel/control_panel.php">Control Panel</a> 
</body>
</html>
