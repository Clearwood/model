<?php
require_once("../includes/config.php");
?>
<!DOCTYPE html>

<html lang="en">

<head>

    <!-- http://getbootstrap.com/ -->
    <!-- <link href="/css/bootstrap.min.css" rel="stylesheet"/> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.css">

    <link href="/css/styles.css" rel="stylesheet"/>
    <title>model DB</title>

    <!-- https://jquery.com/ -->
    <script src="/js/jquery-3.1.0.min.js"></script>
    <link rel="icon" href="https://budde.ws/uploads/favicon.png">
    <!-- http://getbootstrap.com/ -->
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/underscore-min.js"></script>
    <script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>
    <script src="/js/scripts.js"></script>
</head>

<body>
<nav class="navbar navbar-default navbar-custom" id="nav">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navb1"
                    aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand"><img style="max-width:100px; margin-top: -7px;" alt="Brand"
                                         src="./uploads/model.png"></a>
        </div>
        <div class="collapse navbar-collapse" id="navb1">
            <div class="col-lg-3">
                    <form class="formSearch" role="search">
                        <div class="form-group inner-addon right-addon">
                            <span id="the-basics">
                        <label class="sr-only" for="typeahead">full name, id</label>
                        <input type="text" placeholder="Search" class="form-control typeahead" id="typeahead">
                                <span class="glyphicon glyphicon-search"></span>
                        </span>
                        </div>
                    </form>
            </div>
            <div class="col-lg-4">
            </div>
            <div class="col-lg-2">
                <div class="nav navbar-right">
                    <a class="navbar-brand" href="#newmod">New Model</a>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="nav navbar-right">
                    <a class="navbar-brand" href="https://budde.ws/logout.php"><span class="glyphicon glyphicon-log-out"
                                                                                     aria-hidden="true"></span>
                        LOGOUT</a>
                </div>
            </div>
        </div>
</nav>
<div class="jumbotron jumbotron-0">
    <div class="container-fluid">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>first name</th>
                    <th>last name</th>
                    <th>age</th>
                    <th>DELETE ENTRY</th>
                </tr>
                </thead>
                <?php
                //enables sql connection
                $host = "127.0.0.1";
                $password = "Pxoq007^";
                $username = "user";
                $db = "keno_budde_models";
                $port = 3306;
                $connection = mysqli_connect($host, $username, $password, $db, $port);
                if (!$connection) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $file_name = null;
                    if (isset($_FILES['image'])&& !empty( $_FILES["image"]["name"] )) {
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
                    }
                    $fname = $_POST["fname"];
                    $lname = $_POST["lname"];
                    $full_name = $_POST["fname"] . " " . $_POST["lname"];
                    $age = $_POST["age"];
                    if ($stmt = mysqli_prepare($connection, "INSERT IGNORE INTO `models` (`full_name`,`first_name` ,`last_name`,`age`,`file`) VALUES (?,?,?,?,?)")) {
                        mysqli_stmt_bind_param($stmt, "sssis", $full_name, $fname, $lname, $age, $file_name);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                    }
                    redirect("https://budde.ws");
                }
                $result = mysqli_query($connection, "SELECT * FROM `models` ORDER BY id ASC");
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    print("<tr>");
                    print("<td ><a class=\"modelid\" href=\"#model\">" . $row["id"] . "</a></td>");
                    print("<td >" . $row["first_name"] . "</td>");
                    print("<td >" . $row["last_name"] . "</td>");
                    print("<td >" . $row["age"] . "</td>");
                    print("<td ><span class=\"glyphicon glyphicon-remove del\" aria-hidden=\"true\"></span></td>");
                    print("</tr>");
                }
                mysqli_close($connection);
                ?>
            </table>
        </div>
    </div>
</div>
<div class="jumbotron jumbotron-0">
    <div class="container-fluid">
        <div class="page-header" id="newmod">
            <h1>New Model</h1>
        </div>
        <form method="post" enctype="multipart/form-data" action="https://budde.ws/index.php">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-3">
                        <input type="text" class="form-control" placeholder="first name" id="fname" name="fname"
                               required>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" placeholder="last name" id="lname" name="lname"
                               required>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" placeholder="age" name="age" id="age">
                    </div>

                    <div class="col-sm-3">
                        <input type="file" class="form-control" name="image" value="upload picture">
                    </div>
                </div>
                <p></p>
                <button class="btn btn-default btn-block" type="submit">Submit</button>
        </form>
    </div>
</div>
<div class="jumbotron jumbotron-0" id="model">
    <div class="container-fluid">
        <form action="https://budde.ws/update.php"  enctype="multipart/form-data" method="post">
            <input type="file" name="image" id="imgload" style="display:none;"/>
            <input type="text" id="id_form" name="id" style="display:none;" required/>
        <div class="page-header">
            <h1 id="name">Jane Doe</h1>
        </div>
        <div class="container-fluid">
            <div class="col-xs-12 edit">
                <button class="btn btn-default btn-block" type="submit">UPDATE</button>
                <br>
            </div>
            <div class="col-lg-11 descr">
            </div>
            <div class="col-lg-1 descr" id="edit">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> EDIT
            </div>
        </div>
        <div class="col-lg-3">
            <img
                src="https://budde.ws/uploads/woman.jpg"
                class="img-responisve img-fluid img-thumbnail pull-xs-left" id="profile">
        </div>
        <div class="col-lg-3">
            <h2 class="descr" id="age3">age: 24</h2>
            <div class="input-group edit">
                <span class="input-group-addon">Age</span>
                <input type="text" class="form-control" id="age2" name="age" required/>
            </div>


        </div>
        </form>
    </div>
    <div class="container-fluid">
        <a class="btn btn-block" href="#nav">
            <span class="glyphicon glyphicon-menu-up"></span>
            GO BACK TO THE TOP
        </a>
    </div>
</div>
<div class="container-fluid">
    <div id="bottom">
        © Keno Budde 2016
    </div>
</div>
</body>

</html>
