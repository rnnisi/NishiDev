<!DOCTYPE HTML>
<html>
<body>
<h1>Set Scope to Idle</h1>
<b>Warning; This will set the scope status to idle</b><br>
This software maintains a memory of if the scope associated with its rasperry pi is idle or busy. This is to prevent multiple jobs from running. 
Jobs will not run if scope is busy. <br>
If there is a job running on the scope already and the scope is set to idle, the scope will try to do multiple jobs at a time and become overwhelmed.<br>
If a scope is not accepting jobs, but is not currently working on a job, try resetting it to idle.<br>
Nickname should be the name of the pi associated with the scope which you want to specify is idle.<br>
<b>This may take up to a minute to complete.</b>

<form action = "GetIdle.php" method = "get">
Nickname : <input type="text" name="nickname"><br>
<input type="submit" value="Reset Pi to idle">
</form>

</body>
</html>
