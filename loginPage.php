<?php

session_start(); // starts php session which is used for user authorization

if ( isset( $_SESSION['user_id'] ) ) { // if the user is already logged in, send them back home
    header("Location: ./home.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login Here</title>
    <link rel="icon" type="image/x-icon" href="./favicon.ico"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="homepage.css">

</head>
<body>

<div class="container-lg">
    <h1 class="text-center">Login Here:</h1>
    <?php
    // shows message saved in the cookie, which are sucessful account creation or invalid user/pass
    if(strlen($_COOKIE["message"])>1) {
        if (strpos($_COOKIE["message"],"Created")){
            printf("<h1 class=\"text-center text-success\">%s</h1>",$_COOKIE["message"]);
        }else{
            printf("<h1 class=\"text-center text-danger\">%s</h1>",$_COOKIE["message"]);
        }
    }
    setcookie("message", "", time() + (30),"/"); // deletes the cookie
    ?>
    <div class="login-form" style="margin-top:100px">
        <form action="./login.php" method="post">

            <div class="form-group">
                <label for="username">Username: </label>
                <input id="username" name="username" type="text" class="form-control" placeholder="Username" required  >
            </div>
            <div class="form-group">
                <label for="password">Password: </label>
                <input id="password" name="password" type="password" class="form-control" placeholder="Password" required >
            </div>
            <div class="form-group text-center">
                <button type="submit" id="GameWidget" style="text-align: center;">Log in</button>
            </div>
        </form>
        <p class="text-center"><a href="./registerPage.php">Create an Account</a></p>
    </div>
</div>

</body>
</html>