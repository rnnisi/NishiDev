<!DOCTYPE html>
<html>
<head>

<meta name="request" content="width=device-width, initial-scale=1">
<style>
body {
	font-family: "Lato", sans-serif;
	background-color: #EFEFEF;
}

.sidenav {
	height: 100%;
	width: 300px;
	position: fixed;
	z-index: 1;
	top: 0;
	left: 0;
	color: #000000;
	background-color: #A6A6A6;
	overflow-x: hidden;
	padding-top: 20px;
	word-wrap: break-word;
}

.sidenav a {
	text-decoration: none;
	font-size: 20px;
	color: #0086FF;
	display: block;
}

.bottomnav {
    height: 200px;
    width: 100%;
    position: fixed;
	bottom: 0;
	margin-left: 290px;
    color: #000000;
    background-color: #DCDCDC;
    overflow-x: hidden;
    padding-top: 20px;
	padding-left: 20px;
    word-wrap: break-word;
}

.bottomnav a {
    padding: 6px, 8px, 6px, 16px;
	float: left;
    text-decoration: none;
    font-size: 20px;
    color: #335EFF;
    display: block;
}

.dropbtn {
  background-color: #000000;
  color: #FFF300;
  padding: 16px;
  font-size: 23px;
  border: none;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #A6A6A6;
  min-width: 400px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #FFF300;}

.dropdown:hover .dropdown-content {display: block;}

.dropdown:hover .dropbtn {background-color: #A6A6A6;}
.main {
	margin-left: 300px;
	font-size: 28px;
	padding: 0px, 200px;
	margin-bottom: 400px;
}

@media screen and (max-height: 450px) {
	.sidenav {padding-top: 15px;}
	.sidenav a {font-size: 18px;}
}

</style>
</head>
<body>
<div class="sidenav">
    <a href="/control_panel/control_panel.php">
	<img src="NishiDev.png" alt="NishiDev-Logo" width=300px></a><br><br>
	<?php include 'DeviceList.php';?>
</div>

<div class="bottomnav">
	<h2>Newest on Queue</h2>
	<?php
	 $f = "requests.txt";
	$all=file_get_contents($f);
	$lines=explode("\n", $all);
	$n=count($lines)-2;
	if ($n < 8) {
		$limit=$n;
	} else{
		$limit=8;
	}
	for ($ln=$n-$limit; $ln <=$n; $ln++){
		echo $lines[$ln]."<br>";
	} ?>
</div>

<div class="main">
	<div id='dynamic'>
		<br>
		<?php include 'SubmitImportRequest.php' ?>
		<span id='writeto'></span>
	</div>
</div>

</body>
</html>
