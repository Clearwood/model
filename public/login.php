<!DOCTYPE html>

<html lang="en">

    <head>

        <!-- http://getbootstrap.com/ -->
        <!-- <link href="/css/bootstrap.min.css" rel="stylesheet"/> -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.css">

        <link href="/css/styles.css" rel="stylesheet"/>
            <title>models DB</title>
        <link rel="icon" href="https://budde.ws/uploads/favicon.png">
        <!-- https://jquery.com/ -->
        <script src="/js/jquery-3.1.0.min.js"></script>

        <!-- http://getbootstrap.com/ -->
        <script src="/js/bootstrap.min.js"></script>
		<script src="/js/underscore-min.js"></script>
        <script src="/js/scripts.js"></script>

    </head>

    <body>
		<nav class="navbar navbar-default">
  <a class="navbar-brand"><img style="max-width:100px; margin-top: -7px;" alt="Brand" src="./uploads/model.png"></a>
</nav>
<div class="page-header" id="head">
      <h1 id="name">LOGIN</h1>
    </div>
<br></br>
<div id="container-fluid">
            <div id="middle">
			<form action="https://budde.ws/login.php" method="post">
    <fieldset>
        <div class="form-group">
            <input autocomplete="off" id="username" autofocus class="form-control" name="username" placeholder="Username" type="text" required/>
        </div>
        <div class="form-group">
            <input class="form-control" id="password" name="password" placeholder="Password" type="password" required/>
        </div>
        <div class="form-group">
            <button class="btn btn-default" type="submit">
                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                Log In
            </button>
        </div>
    </fieldset>
</form>
	</div>
	<br></br>
<br></br>
        </div>
<?php
require_once("../includes/config.php");
if($_SERVER["REQUEST_METHOD"]=="POST"){

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
$ip = $_SERVER["REMOTE_ADDR"];
mysqli_query($connection, "INSERT INTO `ip` (`address` ,`timestamp`)VALUES ('$ip',CURRENT_TIMESTAMP)");
$result = mysqli_query($connection,"SELECT COUNT(*) FROM `ip` WHERE `address` LIKE '$ip' AND `timestamp` > (now() - interval 10 minute)");
$count = mysqli_fetch_array($result, MYSQLI_NUM);

if($count[0] > 3){
  echo "You are allowed 3 attempts in 10 minutes";
}
else{
	$username=$_POST["username"];
	$row = array();
	if($stmt=mysqli_prepare($connection,"SELECT * FROM users WHERE username=?")){
		mysqli_stmt_bind_param($stmt, "s", $username);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,$row["id"],$row["username"],$row["hash"]);
		mysqli_stmt_fetch($stmt);
		$password=$_POST["password"];
		 if (password_verify($password, $row["hash"]))
            {
                // remember that user's now logged in by storing user's ID in session
                $_SESSION["id"] = $row["id"];
                redirect("/index.php");
            }
		 else{
			 echo "The password or username is wrong";
		 }
		mysqli_stmt_close($stmt);
	}
	}
	mysqli_close($connection);
}

	   
?>
</div>

      <footer class="footer">
      <div class="container">
        <p class="text-muted">© Keno Budde 2016</p>
      </div>
    </footer>

    </body>

</html>