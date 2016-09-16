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
	$query=$_GET["query"];
	$myArray=array();
	if(ctype_digit($query)){
		$quer1=intval($query);

	if($stmt=mysqli_prepare($connection,"SELECT * FROM models WHERE id LIKE ?")){
		mysqli_stmt_bind_param($stmt, "i", $quer1);
		mysqli_stmt_execute($stmt);
		$result=mysqli_stmt_get_result($stmt);
		while($row=mysqli_fetch_assoc($result)){
		$myArray[]=$row;

	}
        mysqli_stmt_close($stmt);
	}
	}
	else{
		if($stmt=mysqli_prepare($connection,"SELECT * FROM models WHERE MATCH(full_name,first_name,last_name) AGAINST (?)")){
		
		mysqli_stmt_bind_param($stmt, "s", $query);
		mysqli_stmt_execute($stmt);
		$result=mysqli_stmt_get_result($stmt);
		while($row=mysqli_fetch_assoc($result)){
		$myArray[]=$row;

	}
            mysqli_stmt_close($stmt);
	}
	}
	header("Content-type: application/json");	
	print(json_encode($myArray, JSON_PRETTY_PRINT));