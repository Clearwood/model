<?php
require("../includes/config.php");
$host = "127.0.0.1";
$password = "Pxoq007^";
$username = "user";
$db = "keno_budde_models";
$port = 3306;
$connection = mysqli_connect($host, $username, $password, $db, $port);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
$id = $_POST["id"];
if($stmt=mysqli_prepare($connection,"SELECT * FROM models WHERE id = ?")) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $file_name = $row["file"];
    $file = $_SERVER["DOCUMENT_ROOT"] . "/uploads/" . $file_name;
    unlink($file);
    mysqli_stmt_close($stmt);
}
if($stmt=mysqli_prepare($connection,"DELETE FROM models WHERE id = ?")) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
mysqli_close($connection);
redirect("/");