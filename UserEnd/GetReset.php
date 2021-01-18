<!DOCTYPE HTML>
<html>
<h1>Reset Submitted.</h1>
<body>

<?php
$f = "requests.txt";
$fh = fopen($f, 'a+');
fwrite($fh, "RESET ! \n");
fclose($fh);
?>
<br><br>
Requests for reset has been submitted. Please wait to be redirected while Pi's recieve reset request.<br>
If you are not redirected in 1 minute, please refresh this page.
<meta http-equiv="refresh" content="30; WipeRequests.php" />
	 
</body>
</html>
