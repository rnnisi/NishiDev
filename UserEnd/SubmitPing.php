<!DOCTYPE HTML>
<html>
<body>
<h1>Ping a Device</h1>
<h2>Format Guidelines</h2>
<b>Nickname</b> should specify what pi you would like to assign the request too. It must be entered exactly as it was at the initialization of that pi.<br>
This feature can be used to check if a device is on the network. If a job request is not being accepted, it is possible the device is disconnected.
<br>
<h2>Submit Request</h2>
<form action = "GetPing.php" method = "get">
Pi (Nickname): <input type="text" name="nickname" required><br>
<input type="submit" value="Send Ping">
</form>

</body>
</html>
