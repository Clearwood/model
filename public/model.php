<?php

    require("../includes/config.php");
	//enables sql connection
	$host = "127.0.0.1";
	$password="Pxoq007^";
	$username="user";
	$db="keno_budde_models";
	$port = 3306;
	$connection = mysqli_connect($host, $username, $password, $db, $port);
		if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
		}
	$id=$_GET["id"];
	$myArray=array();
    if($result=mysqli_query($connection, "SELECT * FROM `models` WHERE id='" . $id . "'")){
	while($row=mysqli_fetch_assoc($result)){
		$myArray[]=$row;
		
	}
	header("Content-type: application/json");	
	print(json_encode($myArray, JSON_PRETTY_PRINT));
	}
	mysqli_close($connection);
    // output places as JSON (pretty-printed for debugging convenience)
    
    

?>