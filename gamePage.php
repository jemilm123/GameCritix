<?php
session_start();
// starts php session which is used for user authorization
$mysqli = new mysqli("localhost","hsehra","cuWraved","hsehra"); // creates a connection to the mysql database

if ($mysqli -> connect_errno){ // check if mysql encoutered an error, if so exit
    echo "Failed to connect to MySQL: " . $mysqli -> connect_errno;
    exit();
}
if (!isset($_GET['id']) || !$_GET['id']){ // if the gameid is not set, go back home as this is a dynamic page
    header("Location: ./home.php");
}

$commandText = "select * from games where gameid=\"".$_GET['id']."\"";
$result = $mysqli->query($commandText);
// queries for the game row where the game id is the get parameter id

$title = "";
$description="";
$genres="";
$platforms="";
$image="";
if ($result){ // if the query was successful
    $row=mysqli_fetch_assoc($result);
    $title = $row['title'];
    $description = addslashes($row['description']);
    $genres=implode(", ",unserialize($row['genre']));
    $platforms=implode(", ",unserialize($row['platform']));
    $image=$row['image'];
    // puts all the information of the game in their respective varaibles
}else{
    header("Location: ./home.php");
}


$commandText = "select * from reviews where gameid=\"".$_GET['id']."\"";
$result = $mysqli->query($commandText);
// query the database for all the reviews that are under the given gameid
$ratingsum=0;
$ratingcount=0;
if ($result) {
    while ( $row = mysqli_fetch_assoc($result)){ // gets the sum of all the ratings and the number of ratings
        $ratingsum+=$row['rating'];
        $ratingcount++;
    }
}




?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <meta charset="UTF-8">
        <link rel="icon" type="image/x-icon" href="./favicon.ico"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link rel="stylesheet" href="gamepage.css"> 
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>

        <script>


            function linktoreviews(){ //Change Link Location to Reviews Page
                window.location = "./reviewPage.php?id=" + <?php echo $_GET['id'];?>;
            }

            function updatePage(){//Change Page Info Here
                var score = document.getElementById("ScorePercent");
                var rating = document.getElementById("ScoreNum");
                var title = document.getElementById("Title");
                var genre = document.getElementById("Genres");
                var platform = document.getElementById("Platforms");
                var desc = document.getElementById("Summary");
                var cover = document.getElementById("CoverArt");
                <?php
                $rating="";
                $ratingText="";
                if($ratingsum==0){
                    $rating="0";
                    $ratingText="0%";
                }else{
                    $rating=strval(round(20*($ratingsum/$ratingcount)/2));
                    if ($rating<50){
                        $ratingText="{$rating}%";
                    }
                    else{
                        $ratingText="{$rating}% Positive";
                    }
                }
                echo "score.style.width = \"{$rating}%\";\n"; //Set to overall score out of 100%
                echo "rating.innerHTML = \"{$ratingText}\";\n"; //Update this as well
                echo "title.innerHTML = \"{$title}\";\n"; //Title of Game
                echo "genre.innerHTML = \"Genres: \" + \"{$genres}\";\n"; //Genres
                echo "platform.innerHTML = \"Platforms: \" + \"{$platforms}\";\n"; //Platforms
                echo "desc.innerHTML = \"{$description}\";\n"; //Description
                echo "cover.src=\"{$image}\";\n"; //Cover Art
                echo "cover.alt=\"{$title}\";\n"; //Game Name

                ?>
            }
        </script>
    </head>

    <body onload="updatePage()">
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
            if ( isset( $_SESSION['user_id'] ) ) {
                echo "<a class=\"btn btn-danger\" id='login' href=\"./logout.php\">Logout</a>";
            }else{
                echo "<a class=\"btn btn-dark\" id='login' href=\"./loginPage.php\">Login</a>";
            }

            ?>
        </div>
    </nav>

        <div id="MainPage">
            <div id="GameArt">
                <img id="CoverArt" Alt="">
            </div>
            <div id="SideContent">
                <div id="GameSummary">
                    <h1 id="Title"></h1>
                    <br>
                    <h4 id="Genres"></h4>
                    <h4 id="Platforms"></h4>
                    <hr>
                    <p id="Summary"></p>
                </div>

                <div id="Score">
                    
                    <div id="ScorePercent">
                        <h3 id="ScoreNum">Unable to Load Reviews</h3>
                    </div>
                </div>
                
                <button name="OtherReviews" id="ReviewLink" onclick="linktoreviews()">
                    <h2>Reviews</h2>
                </button>
                
            </div>
            
        </div>

    </body>
</html>