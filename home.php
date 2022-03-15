<?php
session_start(); // starts php session which is used for user authorization


$mysqli = new mysqli("localhost","hsehra","cuWraved","hsehra"); // creates a new connection to the mysql database

if ($mysqli -> connect_errno){ // check if mysql encountered an error, if so exit
    echo "Failed to connect to MySQL: " . $mysqli -> connect_errno;
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Game Critix</title>
    <link rel="icon" type="image/x-icon" href="./favicon.ico"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="./homepage.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>


    <script>
        function linktogame(id){//Will change the current page to a game page with the selected game's data generated
            window.location = "./gamePage.php?id=" + id;
        }

        function goToOtherGames(){//Links to the first page of allGames
            window.location= "./allGames.php?page=1";
        }

        function loadWidgets(){//Function to load the widgets corresponding to games on the specific page
		//php code retrieves the last 12 created games from database then calls createGames to create widgets for selected games
            <?php

            $commandText = "select * from (select * from games order by gameid DESC limit 12 )sub order by gameid DESC";
            $result = $mysqli->query($commandText);
            // queries the database for the last 12 games added to the website
            if ($result){
                while ($row = mysqli_fetch_assoc($result)) { // calls the create games function on each retrieved game
                    echo "createGames(\"{$row['title']}\",{$row['gameid']},\"{$row['image']}\");\n";
                }
            }

            $result->close();
            $mysqli->close();

            ?>

        }

        function createOthers() {
            var widg = document.createElement("button");
            widg.id = "OtherLinks";
            var title = document.createElement("h1");
            title.innerHTML = "Other Games";
            widg.appendChild(title);
            document.body.appendChild(widg);


        }
        function createGames(name,id,imagelink) { //Constructs a GameWidget with a title, coverart, alt text, link to game page, and then adds it to the widgets on the page
            var widg = document.createElement("button");
            widg.name=name;
            widg.id="GameWidget";
            widg.setAttribute("onClick","linktogame("+id+")");
            var image = document.createElement("img");
            image.id = "GameArt";
            image.src= imagelink;
            image.alt= name;
            var title = document.createElement("h2");
            title.innerHTML=name;
            //title.className="text-center";
            if(name.toString().length<25){
                title.style="line-height: 76.8px;";
            }
            widg.appendChild(image);
            widg.appendChild(title);
            document.getElementById("GameLinks").appendChild(widg);
        }
    </script>

</head>
<body onload="loadWidgets()">
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
<h1 style="margin-left: 5%;">Latest Releases</h1>
<div id="GameLinks">

</div>
<footer>
    <button style="margin-left: 2.5%" id="OtherLinks" class="AllGames" onclick="goToOtherGames()">
        <h1>Other Games</h1>
    </button>
</footer>


</body>
</html>