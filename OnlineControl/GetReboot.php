<!DOCTYPE HTML>
<html>
<h1>Idle command Submitted.</h1>
<body>

<?php
$name= $_GET['nickname'];
$f = "requests.txt";
$fh = fopen($f, 'a+');
fwrite($fh, $name." IDLE ! \n");
fclose($fh);
?>
<br><br>
Request to reset scope to idle has been submitted. If scope is still not accepting requests, please move to trouble shooting in the terminal of the pi. <br> 

Please wait for this page to reload to ensure idle request is properly sent

<meta http-equiv="refresh" content="30; WipeIdle.php" />

</body>
</html>
