<?php

session_start(); // starts php session which is used for user authorization
if ( isset( $_SESSION['user_id'] ) ) { // if user is already logged in, send them back to the home page
    header("Location: ./home.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register Here</title>
    <link rel="icon" type="image/x-icon" href="./favicon.ico"/>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./register.css">
</head>
<body>
<div id="box" class="container">
    <h1 class="text-center">Game Website Register</h1>
    <?php
    // shows cookie for invalid registration
    if(strlen($_COOKIE["message"])>1) {
        printf("<h1 class=\"text-center text-danger\">%s</h1>",$_COOKIE["message"]);
    }
    setcookie("message", "", time() + (30),"/"); // resets the cookie
    ?>
    <div class="login-form" style="margin-top:100px">
        <form action="./register.php" method="post">
            <h2 class="text-center">Register Here</h2>
            <div class="form-group">
                <label for="date1">Date of Birth: </label>
                <input id="date1" type="date" class="form-control" placeholder="YYYY-MM-DD" value="YYYY-MM-DD" required >
                <script>
                    date1.max = new Date("2007-01-01").toISOString().split("T")[0];
                    date1.min = new Date("1900-01-01").toISOString().split("T")[0];
                </script>
            </div>
            <div class="form-group">
                <label for="username">Username: </label>
                <input id="username" name="username" type="text" class="form-control" placeholder="Username" pattern=".{5,32}" required title="5 characters minimum and 32 maximum" >
            </div>
            <div class="form-group">
                <label for="password">Password: </label>
                <input id="password" name="password" type="password" class="form-control" placeholder="Password" pattern=".{5,}" required title="5 characters minimum" >
            </div>
            <div class="form-group text-center">
                <button type="submit" id="Widget">Register</button>
            </div>
        </form>
        <p class="text-center"><a href="./loginPage.php">Already have an account? Login Here</a></p>
    </div>
</div>

</body>
</html>