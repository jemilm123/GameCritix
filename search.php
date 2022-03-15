<?php
session_start(); // starts php session which is used for user authorization

$mysqli = new mysqli("localhost","hsehra","cuWraved","hsehra");  // creates a new connection to the mysql database

if ($mysqli -> connect_errno){ // check if mysql encountered an error, if so exit
    echo "Failed to connect to MySQL: " . $mysqli -> connect_errno;
    exit();
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search</title>
    <link rel="icon" type="image/x-icon" href="./favicon.ico"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="homepage.css">
    <script>
        function linktogame(id){ //Will change the current page to a game page with the selected game's data generated
            window.location = "./gamePage.php?id="+id;
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
<h1 style="text-align: center;">Search Results:</h1>
<div id="GameLinks">
<?php
$Found = false; // variable to hold if any thing is found  from the search
if(isset($_GET['searchterm'])){ // if there is a searchterm as a get parameter
    $commandText = "select * from games where title like \"%".$_GET['searchterm']."%\" or genre like \"%".$_GET['searchterm']."%\"";

    $result = $mysqli->query($commandText);
    // query for all games who's title or genre match the search term parameter
    if($result){
        while ($row = mysqli_fetch_assoc($result)){ // outputs all the games that match the search query
            $title=$row["title"];
            $gameid = $row["gameid"];
            $image=$row['image'];
            $innerstyle="";
            if (strlen($title)<25){
                $innerstyle="line-height: 76.8px;";
            }
            printf('   <button name="%s" id="GameWidget" onclick="linktogame(%s);">  ' .
                '           <img id="GameArt" src="%s">  ' .
                '           <h2 style="%s">%s</h2>  ' .
                '      </button>  ',$title,$gameid,$image,$innerstyle,$title);
            $Found=true; // even if one game was found make this variable true
        }

    }

    if (!$Found){ // if nothing that matched the searchterm was found, output a message saying nothing found
        echo '<h1 style="text-align: center;position: relative;top: -90%; margin-left: -1%">Nothing Found</h1>';
    }

}


?>
</div>

</body>
</html>
