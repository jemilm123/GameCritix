<?php

session_start();
// starts php session which is used for user authorization

if ( !isset( $_SESSION['user_id'] ) || !isset($_POST['gameid']) ) { // if user seession id is not set aka not logged in or game id is not set then go home
    header("Location: ./home.php");
}


$mysqli = new mysqli("localhost","hsehra","cuWraved","hsehra"); // // creates a connection to the mysql database

if ($mysqli -> connect_errno){ // check if mysql encoutered an error, if so exit
    echo "Failed to connect to MySQL: " . $mysqli -> connect_errno;
    exit();
}

$commandText = "delete from reviews where userid=\"".$_SESSION['user_id']."\" and gameid=\"".$_POST['gameid']."\"";
$result = $mysqli->query($commandText);
// sql query to delete from reviews table where id is the user's login id and the post param game id
header("Location: ./reviewPage.php?id=".$_POST['gameid']); // send user back to the review page they were at previously

?>