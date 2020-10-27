<?php
    $servername = "localhost";
    $username = "tatiakqf_admin";
    $pw = "Gottobe$&@me";
    $dbname = "tatiakqf_rusticmojo_db";
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $conn = new mysqli($servername, $username, $pw, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE `users` SET cart = \"\", total_cost = 0 WHERE users.ip = '$user_ip'";
    $result = $GLOBALS['conn']->query($sql);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Successful Transaction!</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700" rel="stylesheet">

        <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
        <link rel="stylesheet" href="css/animate.css">

        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="hero-wrap js-fullheight" style="background-color:LightGreen;">
            <div class="row no-gutters js-fullheight align-items-center justify-content-center">
                <div class="container">
                    <div class="justify-content-center mb-3 pb-3">
                        <div class="col-md-12 heading-section text-center ftco-animate">
                            <h1 class="big">Thank You!</h1>
                            <h2 class="mb-4" href="index.php"> Successful Purchase, <a href="index.php" style="color:white;">CLICK HERE</a> to go Home.</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="js/jquery.min.js"></script>
        <script src="js/jquery-migrate-3.0.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.waypoints.min.js"></script>
        <script src="js/jquery.stellar.min.js"></script>
        <script src="js/owl.carousel.min.js"></script>
        <script src="js/aos.js"></script>
        <script src="js/scrollax.min.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
