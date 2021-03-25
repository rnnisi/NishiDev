<!DOCTYPE HTML>
<html>
<body>
<h1>Request Experiment</h1>
<h2>Format Guidelines</h2>
<b>Nickname</b> should specify what pi you would like to assign the request too. It must be entered exactly as it was at the initialization of that pi.<br>
You have the option to add a description for the Pi. 

<h2>Submit Request</h2>
<form action = "GetNewPi.php" method = "get">
Pi (Nickname): <input type="text" name="nickname" required><br>
Description :<input type="text" name="description"><br>
<input type="submit" value="Add Device">
</form>

</body>
</html>
