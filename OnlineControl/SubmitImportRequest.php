<!DOCTYPE HTML>
<html>
<body>
<h1>Import Old Request for New Job</h1>
<h2>Format Guidelines</h2>
<b>Job ID</b> should be in format "Req_" to specify the previous run you would like to base the new one off of.<br>
<h2>Submit Request</h2>
<form action = "ImportRequestEdit.php" method = "post">
<b>Job ID:</b>   Req_<input type="text" name="ID"><br>
<input type="submit" value="Import Request">
</form>

</body>
</html>
