<?php

session_start(); // starts php session which is used for user authorization

$mysqli = new mysqli("localhost","hsehra","cuWraved","hsehra"); // creates a new connection to the mysql database

if ($mysqli -> connect_errno){ // check if mysql encountered an error, if so exit
    echo "Failed to connect to MySQL: " . $mysqli -> connect_errno;
    exit();
}

if (!isset($_GET['id']) || !$_GET['id']){ // if no id is given for the dynamic review page, go back to home
    header("Location: ./home.php");
}

$loggedin = false; // variable holds if the user is logged in
$username="";
$commented=false; // holds if the user already commented
if(isset( $_SESSION['user_id'] ) ){ // if user is logged in
    $loggedin=true; // set login to true
    $commandText = "select * from login where id=\"".$_SESSION['user_id']."\"";
    $result = $mysqli->query($commandText);
    if($result) { // query the logged in user for their username
        $row = mysqli_fetch_assoc($result);
        $username = $row["username"];
    }

    $commandText = "select * from reviews where gameid=".$_GET['id']." and userid=".$_SESSION['user_id'];
    $result = $mysqli->query($commandText);
    $selfRated ="";
    $selfCommented="";
    $selfDateTime="";
    if($result){ // query all the reviews by the user that have the same game id get parameter
        $row = mysqli_fetch_assoc($result);
        $selfRated=$row['rating'];
        $selfCommented=$row['comment'];
        $selfDateTime=$row['datetime'];
        if($row['userid']==$_SESSION['user_id']){ // if found the user, set them as already commented
            $commented=true;
        }
    }
}

$commandText = "select * from games where gameid=\"".$_GET['id']."\"";
$result = $mysqli->query($commandText);
$title= "";
if($result) { // query the game for its title
    $row = mysqli_fetch_assoc($result);
    $title = $row['title'];
}else{ // if the gameid doesnt exist go back home
    header("Location: ./home.php");
}


?>


<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title;?> Reviews</title>
        <meta charset="UTF-8">
        <link rel="icon" type="image/x-icon" href="./favicon.ico"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link rel="stylesheet" href="reviewpage.css"> 
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>

        <script>
            $( document ).ready(function() {
                $(function () {
                    $(".selfRate").rateYo({
                        rating: 0,
                        halfStar: true,
                        onSet: function (rating) {
                            $("#rating").val(String(rating));
                        }
                    });
                });
            });

            function validateForm() {
                if ($("#comment").val().length === 0){
                    alert("Comment Can't Be Empty");
                    return false;
                }
                if (parseFloat($("#rating").val())<=0 || parseFloat($("#rating").val())>5 ){
                    alert("Rating Not Set");
                    return false;
                }
            }
        </script>

        <script>




            function cutusername(user) {//Function that cuts username if it's too long, replacing the extra characters beyond 20 with "..."
                if (user.length > 20) {
                    return user.substring(0, 20) + "...";
                } else {
                    return user;
                }
            }
            function createReview(Author, rating, comment, timeposted) {//Creates a review widget with a username, stars, the review, and time posted, then adds it to the list of reviews displayed on the page
                var review = document.createElement("div");
                var username = document.createElement("div");
                var stars = document.createElement("div");
                var content = document.createElement("div");
                var time = document.createElement("div");
                var user = document.createElement("h2");
                username.append(user);
                review.id = "Review";
                username.id = "Username";
                stars.id = "Stars";
                content.id = "Content";
                user.innerHTML = cutusername(Author);
                
                //Stars are added here
                $(stars).rateYo({
                    rating: rating,
                   halfStar: true,
                    readOnly: true,
                });
                var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute:'2-digit' };
                var datetime= new Date(timeposted);
                var offset = new Date().getTimezoneOffset();
                datetime.setMinutes(datetime.getMinutes() - offset);


                content.innerHTML = comment;
                time.innerHTML = datetime.toLocaleString("en-US", options);
                review.appendChild(stars);
                review.appendChild(username);
                review.appendChild(content);
                review.appendChild(time);

                document.getElementById("ReviewWidgets").appendChild(review);
            }
            function loadReviews() {//Loads review info from database with php and calls CreateReview to create new review widgets
                <?php

                $commandText = "select * from reviews where gameid=\"".$_GET['id']."\"";
                $result = $mysqli->query($commandText);
                // queries all the reviews with the given game id
                while ($row = mysqli_fetch_assoc($result)) { // loops through all the reviews rows
                    $usernameOfComment = "";
                    $commandText = "select * from login where id=\"" . $row['userid'] . "\"";
                    $result2 = $mysqli->query($commandText);
                    if ($result2) { // query for the username of the user by the comment userid
                        $row2 = mysqli_fetch_assoc($result2);
                        $usernameOfComment = $row2["username"];
                    }
                    $comment = $row["comment"];
                    $rating = $row["rating"];
                    $rating = intval($rating)/2;
                    $userid = $row['userid'];
                    $time = $row['datetime'];
                    // stores the comment's information to their corresponding variables
                    if(!($userid == $_SESSION['user_id'])){ // output all the reviews with the default template except for the review by the logged in user
                        echo "createReview(\"{$usernameOfComment}\",\"{$rating}\",\"{$comment}\",\"{$time}\");\n";
                    }
                }

                ?>

            }
        </script>
    </head>

    <body onload="loadReviews()">
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

        <?php
        // prints a message for login required for reviews or if the user logged in has already commented
        if ($loggedin == true){
            if($commented == true){
                echo '<h1>You Already Commented</h1>';
            }
        }
        else{
            echo '<h1>You Need to be Logged in to leave a Review</h1>';
        }
        ?>

        
        <div id="ReviewWidgets">
            <div id="Review" class="OwnReview" <?php
            // Shows the submission review wdiget if the user is logged in and has not commented yet
            if ($loggedin == true){
                if($commented == true){
                    echo "style=\"display: none;\"";
                }else{
                    echo "";
                }
            }
            else{
                echo "style=\"display: none;\"";
            }
            ?> >
                <form id="form1" action="./postComment.php?id=<?php echo $_GET['id'] ?>" method="post">
                    <div id="Stars" class="selfRate">
                        Stars Placement
                    </div>
                    <div id="Username">
                        <h2>You Are Posting: <?php echo $username;?></h2>
                    </div>
                    <br>
                    <input type="text" name="rating" id="rating" value="0" hidden required>
                    <input type="number" name="gameid" id="gameid" value="<?php echo $_GET['id'] ?>" hidden required>
                    <textarea class="form-control" id="comment" name="comment" rows="13" form="form1" placeholder="Comment Here"></textarea>
                    <br>
                    <input type="submit" name="Submit" value="Submit" class="float-right btn btn-outline-light ml-2" onclick="return validateForm()">
                </form>
            </div>

            <div id="Review" class="OwnReview" <?php
            // if the user has already commented, show their comment at the top in a bigger template
            if($commented == true){
                echo "";
            }else{
                echo "style=\"display: none;\"";
            }
            ?>>
                
                    <div id="Stars" class="selfRated">
                        Stars Placement
                    </div>
                    <?php
                    // prints the javascript that will set the star rating for the logged in user's comment
                    printf(
                            '   <script>  '  .
                             '   $(".selfRated").rateYo({  '  .
                             '   rating: %s,  '  .
                             '   halfStar: true,  '  .
                             '   readOnly: true,  '  .
                             '    });  '  .
                             '  </script>  ', $selfRated/2);
                    ?>
                    <div id="Username">
                        <h2><?php echo $username;?></h2>
                    </div>
                    <div id="Content">
                        <p><?php echo $selfCommented;?></p>
                    </div>
                    <br>
                    <div>
                        <form action="./delComment.php?id=<?php echo $_GET['id'] ?>" method="post">
                            <input type="submit" name="Delete" value="Delete" class="float-right btn btn-danger btn-outline-light ml-2">
                            <input type="number" name="gameid" id="gameid" value="<?php echo $_GET['id'] ?>" hidden required>
                        </form>
                    </div>
                    <div id="Time">
                        <p class="selfDateTime"></p>
                        <?php
                            // prints the date and time the logged in user posted their comment
                            printf(
                            "<script>\n
                            var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute:'2-digit' };\n
                            var datetime= new Date(\"%s\");\n
                            var offset = new Date().getTimezoneOffset();\n
                            datetime.setMinutes(datetime.getMinutes() - offset);\n
                           $(\".selfDateTime\").text(datetime.toLocaleString(\"en-US\", options));\n                        
                           </script>",$selfDateTime);

                        ?>
                    </div>
            </div>

            
        </div>
        

    </body>
</html>