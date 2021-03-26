<!DOCTYPE HTML>
<html>
<body>
<h1>Manually Specify Experiment Request</h1>
<h2>Format Guidelines</h2>
<b>Nickname</b> should specify what pi you would like to assign the request too. It must be entered exactly as it was at the initialization of that pi.<br>
<b>RunTime</b> should be the desired length of experiment, in seconds.<br>
<b>Trigger</b> should be "auto" for automatic trigger mode; "force" to force the trigger with each collection; or "X.XV" to set trigger to desired voltage value.<br>
<b>Channels</b> field should be used to specify what channels you want to read data from. Enter multiple channels in the format "A, B, C...". If no value is given, all channels will be read. <br>


<div id="readfrom", style="display: none">
Channel: <input name="FGchan" type="text">
	<select name="action" type="text">
	<option>Action</option>
	<option value="wait">Wait</option>
    <option value="sine">Sine Wave</option>
    <option value="square">Square Wave</option></select>
Specs: <input name="specs" type="text">
</div>

<br><br>
<form id="requests" action = "GetExperiment.php" method = "post">

Pi (Nickname): <input type="text" name="ScopeName" required><br>
RunTime: <input type="text" name="DAQRunTime" required ><br>
Trigger:<input type="text" name="ScopeTrigger" required><br>
Channels :<input type="text" name="ScopeChan"><br>
<h2>Submit Request to Function Generator</h2>
<!---
<select name="saved" type="text">
<option>Function Run Parameters</option>
	<option value="manual">Manually Enter Run</option>
</select>
--!>
<span id="writeto"></span>
<input type="button" onclick="FuncGen();" value="Add requests for Function Generator"><br><br>
<br><input type="submit" value="Make Scope Request">
</form>
<script>
	function hi(){
		alert("hi");
	}
</script>

<script>
    var counter = 0;
    function FuncGen(){
        counter++;
        var x = document.getElementById('readfrom').cloneNode(true);
		x.id = '';
		x.style.display = 'block';
		var newField = x.childNodes;
		for (var i=0;i<newField.length;i++) {
			var theName = newField[i].name
			if (theName){
				newField[i].name = theName + counter;}
		}
		var insert = document.getElementById('writeto');
		var update = document.getElementById('readfrom');
		update.parentNode.insertBefore(x,update);
		insert.parentNode.insertBefore(x,insert);
	}
window.onload=FuncGen();
</script>



</body>
</html>
