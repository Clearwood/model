<?php
require_once("../includes/config.php");
$host = "127.0.0.1";
$password="Pxoq007^";
$username="user";
$db="keno_budde_models";
$port = 3306;
$connection = mysqli_connect($host, $username, $password, $db, $port);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
if(isset($_POST["file"])){

}
/**
 * Created by PhpStorm.
 * User: Keno
 * Date: 17.09.2016
 * Time: 11:36
 */