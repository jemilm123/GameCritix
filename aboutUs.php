<?php
session_start();
// starts php session which is used for user authorization
?>
<!DOCTYPE html>
<html>
<head>
	<title>Game Critix - About Us</title>
	<meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="./favicon.ico"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
	<link rel="stylesheet" href="aboutus.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #7f5af0;">
    <a class="navbar-brand" href="./home.php"><img src="logo.png" alt="Game Critix" height="75px"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="./allGames.php?page=1">All Games</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./consoleLinks.php">Sort By Console</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./aboutUs.php">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./contactUs.php">Contact Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./requestGame.php">Request Game</a>
            </li>
        </ul>
        <form class="form-inline w-50 my-2 " action="./search.php">
            <label for="searchterm" style="color: white"><b>Search:&emsp;</b> </label><input class="form-control w-50 mr-sm-2" id="searchterm" name="searchterm" type="text" placeholder="Type Here">
            <button class="btn btn-dark my-2 my-sm-0" type="submit">Search</button>
        </form>
        <?php
		// These conditions check for if the session user_id is set indicating a user a logged in, when the user is logged in, show a logout button otherwise a login button
        if ( isset( $_SESSION['user_id'] ) ) {
            echo "<a class=\"btn btn-danger\" id='login' href=\"./logout.php\">Logout</a>";
        }else{
            echo "<a class=\"btn btn-dark\" id='login' href=\"./loginPage.php\">Login</a>";
        }

        ?>
    </div>
</nav>
<br>
<h1 style="margin-left: 5%">About Us</h1>
<br>
<div class="container" style="margin-left: 0.4%">
	<p style="margin-left: 5%;">GameCritix is a proud member of the gaming community. We hope to bring you accurate information on games across all platforms as well as the ability to connect with other members of the community by writing and viewing reviews of various games. If you do not see a game you were hoping to find on our website, please don't hesistate to fill in our "Request a Game" form and we will try to fulfill your request as soon as possible!</p>
</div>
</body>
</html>