<?php
require_once("../includes/config.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $file_name = null;
    $age = $_POST["age"];
    if (isset($_POST["file"])) {
        if ($stmt = mysqli_prepare($connection, "SELECT * FROM models WHERE id = ?")) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            $file_name = $row["file"];
            $file = $_SERVER["DOCUMENT_ROOT"] . "/uploads/" . $file_name;
            unlink($file);
            mysqli_stmt_close($stmt);
        }
        if (isset($_FILES['image'])) {
            $errors = array();
            $date1 = getdate();
            $date = $date1["mon"] . "_" . $date1["mday"] . "_" . $date1["year"] . "_" . $date1["hours"] . "_" . $date1["minutes"] . "_" . $date1["seconds"];
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_type = $_FILES['image']['type'];
            $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));
            $basename = basename($file_name, $file_ext);
            $file_name = $date . $basename . $file_ext;
            $expensions = array("jpeg", "jpg", "png");

            if (in_array($file_ext, $expensions) === false) {
                $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
            }

            if ($file_size > 2097152) {
                $errors[] = 'File size must be excately 2 MB';
            }

            if (empty($errors) == true) {
                move_uploaded_file($file_tmp, $_SERVER["DOCUMENT_ROOT"] . "/uploads/" . $file_name);
                echo "<script>console.log(\"SUCCESS\")</script>";
            } else {
                print_r($errors);
            }
            if ($stmt = mysqli_prepare($connection, "UPDATE `models` SET age=?, file=? WHERE id=?")) {
                mysqli_stmt_bind_param($stmt, "isi", $age, $file_name, $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
            redirect("https://budde.ws");
        }
    } else {
        if ($stmt = mysqli_prepare($connection, "UPDATE `models` SET age=? WHERE id=?")) {
            mysqli_stmt_bind_param($stmt, "isi", $age, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        redirect("https://budde.ws");
    }
}
else{
    redirect("https://budde.ws");
}
/**
 * Created by PhpStorm.
 * User: Keno
 * Date: 17.09.2016
 * Time: 11:36
 */