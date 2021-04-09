<?php
	$config["MySQL"]["host"] = "localhost";
	$config["MySQL"]["username"] = "orditi22_odtmcz";
	$config["MySQL"]["password"] = "@OdtMcz";
	
	$config["MySQL"]["database"] = "orditi22_maceio";
	
	$connection = mysqli_connect($config["MySQL"]["host"], $config["MySQL"]["username"], $config["MySQL"]["password"]);
	mysqli_select_db($connection, $config["MySQL"]["database"]);
?>

