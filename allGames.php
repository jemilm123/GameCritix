<?php
session_start(); // starts php session which is used for user authorization


$mysqli = new mysqli("localhost","hsehra","cuWraved","hsehra"); // creates a new connection to the mysql database

if ($mysqli -> connect_errno){ // check if mysql encountered an error, if so exit
    echo "Failed to connect to MySQL: " . $mysqli -> connect_errno;
    exit();
}

if (isset($_GET['page']) == false){ // if page parameter is not set exit.
    header("Location: ./home.php");
}


$isLastPage=false; // used to check if the current page is the last page


// conditions used to retreive either all games or games sorted by consoles with the 'sortby' get parameter
if(isset($_GET['sortby'])){
    $commandText="select count(gameid) from games where platform like \"%".$_GET['sortby']."%\"";
}else{
    $commandText = "select count(gameid) from games";
}

//queries get the count of gameid in total, and depending on if they are filtered by 'sortby' param
$result = $mysqli->query($commandText); // gets the result from the query
$count=null;
if($result){
    $row=mysqli_fetch_assoc($result);
    $count = $row['count(gameid)'];
    if($_GET['page']==(ceil($count/4))){ // calculates if the current page is the last page according to mysql rows
        $isLastPage=true;
    }
}
if($count!=null){ // if the user is at an invalid page number, go back home
    if (($_GET['page']>ceil($count/4)) || ($_GET['page']<=0)){
        header("Location: ./home.php");
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Games Page <?php echo $_GET['page']?></title>
    <link rel="icon" type="image/x-icon" href="./favicon.ico"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="./homepage.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>


    <script>
        function linktogame(id){ //Will change the current page to a game page with the selected game's data generated
            window.location = "./gamePage.php?id=" + id;
        }

        function gotopage(pagenum){ //Will progress to either the next or previous page
            window.location = "./allGames.php?page=" + pagenum;
        }

        function loadWidgets(){//Function to load the widgets corresponding to games on the specific page
		//php code retrieves the 4 games corresponding to the page then calls createGames to create widgets for selected game
            <?php

            $offset = ($_GET['page']-1)*4;
            if(isset($_GET['sortby'])){
                $commandText = "select * from games where platform like \"%".$_GET['sortby']."%\" limit 4 offset ".$offset;
            }else{
                $commandText = "select * from games limit 4 offset ".$offset ;
            }


            $result = $mysqli->query($commandText);
            if ($result){
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "createGames(\"{$row['title']}\",{$row['gameid']},\"{$row['image']}\");\n";
                }
            }

            $result->close();
            $mysqli->close();

            ?>

        }


        function createGames(name,id,imagelink) //Constructs a GameWidget with a title, coverart, alt text, link to game page, and then adds it to the widgets on the page
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

<div id="GameLinks">

</div>
<div id="PageButtons">
    <button id="OtherLinks" onclick="gotopage(
        <?php
            // outputs the page number with the same get parameters to the previous page
            echo "'";
            echo ($_GET['page']-1);
            if(isset($_GET['sortby'])){
                echo "&sortby=".$_GET['sortby'];
            }
            echo "'";
        ?>
    )">
        <h1>
            <?php
                // Outputs the text to display to the user for previous page button
                if($_GET['page']==1){
                    echo "Go Back to the Home Page";
                }
                else {
                    echo "Go Back to Page ".($_GET['page']-1);
                }
            ?>
        </h1>
    </button>
    <button id="OtherLinks" onclick="gotopage(
        <?php
            // outputs the page number with the same get parameters to the next page
            echo "'";
            echo ($_GET['page']+1);
            if(isset($_GET['sortby'])){
                echo "&sortby=".$_GET['sortby'];
            }
            echo "'";
        ?>
    )"
    <?php

        if($isLastPage == true){ // if the current page is the last page then dont show the next page button
            echo "style=\"display: none;\"";
        }
    ?>>
        <h1>
            <?php
                // Outputs the text to display to the user for next page button
                echo "Go to Page ".($_GET['page']+1);
            ?>
        </h1>
    </button>
</div>


</body>
</html>