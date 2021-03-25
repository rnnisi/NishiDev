<!DOCTYPE HTML>
<html>
<body>
<h1>Reset Request List</h1>
<b>Warning; This will cancel all queued jobs and permanently delete queue log</b><br>
This will not remove any experimental data, effect experimental logs, or experiment file numbering system<br>
<b>This will not cancel jobs which have already been submitted and are in progres.</b><br>
A pi that is busy with an alrady submitted job will reset after completing that job. <br><br><br>
<b>This will take about a minute to complete.</b>

<form action = "GetReset.php" method = "get">
<input type="submit" value="Submit Reset to pi's">
</form>

</body>
</html>
