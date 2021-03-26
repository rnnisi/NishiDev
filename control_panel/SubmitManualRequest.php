<!DOCTYPE HTML>
<html>
<body>
<style>
.callout{
	max-width: device-width -700px;
	left: 500px;
}
.callout-header{
	padding: 55px, 35px;
	background: #FFF300;
	font-size: 20px;
	color: black;
	font-style: italic;
}
</style>
<h1>Manually Specify Experiment Request</h1>
<h2>Format Guidelines</h2>
<b>Nickname</b> should specify what pi you would like to assign the request too. It must be entered exactly as it was at the initialization of that pi.<br>
<b>RunTime</b> should be the desired length of experiment, in seconds.<br>
<b>Trigger</b> should be "auto" for automatic trigger mode; "force" to force the trigger with each collection; or "X.XV" to set trigger to desired voltage value.<br>
<b>Channels</b> field should be used to specify what channels you want to read data from. Enter multiple channels in the format "A, B, C...". If no value is given, all channels will be read. <br>
<br><br>
The function generator and other accessory items will take longer to recieve requests than the master node, which is collecting electronic signal response. 


It is reccomended to set up data acquisition assuming waveforms will be collected for about ~1 min before the function generator program executes its first command. This is achieved through the following reccomendations: 

<ol>
	<li>Start with all channels on the Function Generator off.</li>
	<li>Command set should start by setting each channel to output the desired signal</li>
	<li>Once each channel is configured, then turn each channel on as desired.</li>
	<li>Use "wait" times to maintain the specified settings before moving onto the next commands</li>
	<li>Command set should end by turning all channels off</li>
	<li>Total runtime for the scope should be greater than sum of wait times + five seconds for each command + 1 min lag time for best results.</li>
</ol>

There is an example provided below.

<div id="readfrom", style="display: none">
	Channel: <input name="FGchan" type="text">
	Action: <select id='menu' name='action' onchange='CheckDropDown(value);'>
    <option value="OUT_ON">Output On</option>
    <option value="OUT_OFF">Output Off</option>
	<option value="WAIT">Wait</option>
    <option value="SINE">Sine Wave</option>
    <option value="SQUARE">Square Wave</option>
    <option value="PULSE">PulseWave</option>
    <option value="DC">DC Output</option>
    <option value="RAMP">Ramp Wave</option>
    <option value="NOISE">Output Noise</option>
    <option value="ARB">Arbitrary Wave</option>
    <option value="PRBS">PRBS Wave</option>
    <option value="FREQ">Set Frequency</option>
    <option value="PERIOD">Set Period</option>
    <option value="VPP">Set Peak to Peak Amplitude</option>
    <option value="OFFSET">Set Offset</option>
    <option value="PHASE">Set Phase</option>
	</select>
	Specs: <input id='opt' name='specs_temp' type='text'>
</div>

<br><br>
<form id="requests" action = "GetManualExperiment.php" method = "post">

Pi (Nickname): <input type="text" name="ScopeName" required><br>
RunTime: <input type="text" name="DAQRunTime" required ><br>
Trigger:<input type="text" name="ScopeTrigger" required><br>
Channels :<input type="text" name="ScopeChan"><br>
<h2>Submit Request to Function Generator</h2>
<div class="callout">
	<div id='instr' class="callout-header">Intructions for specifications will appear here.</div>
</div>
<br>


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

<script>
	var noSpecs = "OUT_ON OUT_OFF SINE SQUARE RAMP PULSE NOISE ARB DC PRBS";
	function CheckDropDown(val){
		if (noSpecs.includes(val)){
			newText="No specifications taken. Leave specs box empty.";}
		if (val=="FREQ"){
			newText = "Input frequency, in kHz, to specs box.";}
		if (val == "PERIOD"){
			newText = "Enter period, in seconds, to specs box.";}
        if (val=="VPP"){
            newText = "Input amplitude (peak to peak), in volts(V), to specs box.";}
        if (val == "OFFSET"){
            newText = "Enter offset, in volts (V), to specs box.";}
        if (val=="PHASE"){
            newText = "Input phase, in degrees, to specs box.";}
        if (val == "WAIT"){
            newText = "Enter wait time, in seconds, to specs box.";}
		document.getElementById('instr').innerHTML = val + ": " + newText;
}
</script>
</body>


</html>
