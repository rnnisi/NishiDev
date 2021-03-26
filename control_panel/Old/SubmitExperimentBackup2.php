<!DOCTYPE HTML>
<html>
<body>
<h1>Request Experiment</h1>
<h2>Format Guidelines</h2>
<b>Nickname</b> should specify what pi you would like to assign the request too. It must be entered exactly as it was at the initialization of that pi.<br>
<b>RunTime</b> should be the desired length of experiment, in seconds.<br>
<b>Trigger</b> should be "auto" for automatic trigger mode; "force" to force the trigger with each collection; or "X.XV" to set trigger to desired voltage value.<br>
<b>Channels</b> field should be used to specify what channels you want to read data from. Enter multiple channels in the format "A, B, C...". If no value is given, all channels will be read. <br>

<form id="requests" action = "GetExperiment.php" method = "post">
<h3>Data Collection</h3>
Pi (nickname of pi attatched to desired scope): <input type="text" name="nickname" required><br>
RunTime: <input type="text" name="RunTime" required ><br>
Trigger:<input type="text" name="trigger" required><br>
Channels :<input type="text" name="channels"><br>
<span id="writeto"></span>
<br><input type="submit" value="Make Scope Request">
</form>

<br><button onclick="FuncGenRequest()">Add Function gen request</button>
<script>
	function hi(){
		alert("hi");
	}
</script>
<script>
	function Base(){
		var form = document.createElement("form")
		form.setAttribute.action="GetExperiment.php"
<script>
	var counter = 0;
	function FuncGenRequest(){
		counter++;
		var x = document.getElementById("writeFGto");
		var txt_1 = "Output Channel:    ";
		var setChan = document.createElement("input");
		setChan.setAttribute.type="text";
		setChan.setAttribute.name="FG_ch" + counter;
		var txt_2 = "Action:    ";
		var newCommand = document.createElement("input");
		newCommand.setAttribute.type="text";
		newCommand.setAttribute.name="Action" + counter;
		var txt_3 = "Specifications:    ";
		var params = document.createElement("input");
		params.setAttribute.type="text";
		params.setAttribute.name="FG_params" +counter;
		var newLine = document.createElement("p");
		x.append(newLine);
		x.append(txt_1);
		x.append(setChan);
		x.append(txt_2);
		x.append(newCommand);
		x.append(txt_3);
		x.append(params);
		x.append(newLine);
	}
</script>




</body>
</html>
