<?php
	$config["MySQL"]["host"] = "mysql-server";
	$config["MySQL"]["username"] = "root";
	$config["MySQL"]["password"] = "secret";
	
	$config["MySQL"]["database"] = "orditi";
	
	$connection = mysqli_connect($config["MySQL"]["host"], $config["MySQL"]["username"], $config["MySQL"]["password"]);
	mysqli_select_db($connection, $config["MySQL"]["database"]);
?>

