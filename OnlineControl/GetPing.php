<!DOCTYPE HTML>
<html>
<h1>Ping Results</h1>
<body>
<?php 
$nickname = $_GET['nickname'];
?>

<br>

<?php
$cmd = "./FindIP.sh ".$nickname;
$IP = str_replace(' ', '', shell_exec($cmd));
$IP = str_replace("\n",'', $IP);
if (strlen($IP) >= 8) {
	echo $nickname." found on local network at IP adress ".$IP;
    } else {
        echo "Cannot find ".$nickname." on local network.<br><br>";
    }

?>
<br><br>
Back to <a id="home" href="/control_panel/control_panel.php">Control Panel</a> 
</body>
</html>
