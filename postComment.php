<?php
session_start(); // starts php session which is used for user authorization

if ( !isset( $_SESSION['user_id'] ) ) { // if user isnt logged in, send them back home
    header("Location: ./home.php");
}


$mysqli = new mysqli("localhost","hsehra","cuWraved","hsehra"); // creates a new connection to the mysql database

if ($mysqli -> connect_errno){ // check if mysql encountered an error, if so exit
    echo "Failed to connect to MySQL: " . $mysqli -> connect_errno;
    exit();
}


function invalidErr(){ // General function for when an invalid parameter is given or error occurs
    setcookie("message", "Invalid Review Info", time() + (30), "/");
    header("Location: ./home.php");
    exit();
}

if (!isset($_POST['gameid']) || !isset($_POST['rating']) || !isset($_POST['comment'])){ // trigger error function if gameid, rating and comment isnt given
    invalidErr();
}
$rating = $_POST['rating'];
$rating = intval($rating*2); // multiplies the rating by 2 before storing since half stars can be given out of 5
$comment = $_POST['comment'];
$comment = $mysqli->real_escape_string($comment); // cleans up error causing comment strings in sql by escaping it
$commandText = "INSERT INTO reviews (userid,gameid,rating,comment) VALUES ('".$_SESSION['user_id']."','".$_POST['gameid']."','".$rating."','".$comment."')";

$result = $mysqli->query($commandText);
//query the database and insert the comment given by the user into the database with its corresponding gameid

header("Location: ./reviewPage.php?id=".$_POST['gameid']); // sends the user back to the review page they were on
?>

