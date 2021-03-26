<!DOCTYPE HTML>
<html>
<body>
<h1>Request Experiment</h1>
<h2>Format Guidelines</h2>
<b>Nickname</b> should specify what pi you would like to assign the request too. It must be entered exactly as it was at the initialization of that pi.<br>
<b>RunTime</b> should be the desired length of experiment, in seconds.<br>
<b>Trigger</b> should be "auto" for automatic trigger mode; "force" to force the trigger with each collection; or "X.XV" to set trigger to desired voltage value.<br>
<b>Channels</b> field should be used to specify what channels you want to read data from. Enter multiple channels in the format "A, B, C...". If no value is given, all channels will be read. <br>

<h2>Submit Request</h2>
<form action = "GetExperiment.php" method = "get">
Pi (Nickname): <input type="text" name="nickname" required><br>
RunTime: <input type="text" name="RunTime" required ><br>
Trigger:<input type="text" name="trigger" required><br>
Channels :<input type="text" name="channels"><br>
<input type="submit" value="Make Request">
</form>

</body>
</html>
