<?php
session_start();
// starts php session which is used for user authorization

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sort By Console</title>
    <link rel="icon" type="image/x-icon" href="./favicon.ico"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="consolelinks.css"> 
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <script>
        function gotoLink(searchterm){//Will link to a search page that searches for the console selected
            window.location = "./allGames.php?page=1&sortby=" +searchterm;
        }

    </script>

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
    <h1 style="margin-left: 5%;">Sort By Consoles</h1>
    <div id="Consoles">
        <button id="ConsoleWidget" onclick="gotoLink('switch')">
            <img id="ConsoleArt" src="https://i.imgur.com/ZtBq8PW.png">
            <div id="Info">
                <h2>Nintendo Consoles</h2>
                Includes the Nintendo Switch and other recent Nintendo-related consoles, such as the Wii U and the Wii.
            </div>
        </button>

        <button id="ConsoleWidget" onclick="gotoLink('playstation')">
            <img id="ConsoleArt" src="https://i.imgur.com/o56eY4T.png">
            <div id="Info">
                <h2>PlayStation Consoles</h2>
                <p>Includes the PlayStation 5 and other recent Sony-related consoles, such as the PlayStation 4 and the Psp.</p>
            </div>
        </button>

        <button id="ConsoleWidget" onclick="gotoLink('xbox')">
            <img id="ConsoleArt" src="https://siliconangle.com/files/2013/05/xbox-logo-square.jpg">
            <div id="Info">
                <h2>Microsoft Consoles</h2>
                <p>Includes the XBox Series X and other recent Microsoft-related consoles, such as the XBox One.</p>
            </div>
        </button>

        <button id="ConsoleWidget" onclick="gotoLink('windows')">
            <img id="ConsoleArt" src="https://www.logolynx.com/images/logolynx/28/281f1daf265a953c676b4d5afeadbae8.png">
            <div id="Info">
                <h2>PC</h2>
                <p>Includes all Computer-based games, such as Windows, Mac, and Linux</p>
            </div>
        </button>
        
    </div>
    




</body>
</html>