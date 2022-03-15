<?php
session_start(); // starts php session which is used for user authorization


$mysqli = new mysqli("localhost","hsehra","cuWraved","hsehra"); // creates a new connection to the mysql database

if ($mysqli -> connect_errno){ // check if mysql encountered an error, if so exit
    echo "Failed to connect to MySQL: " . $mysqli -> connect_errno;
    exit();
}

if (!isset($_POST['GameName']) || !isset($_POST['GameUrl']) ){ // Go back to requestGame page if posted gamename and gameurl aren't present
    header("Location: ./requestGame.php");
}

$commandText = "INSERT INTO requests (name,url) VALUES ('".$_POST['GameName']."','".$_POST['GameUrl']."')";
$result = $mysqli->query($commandText);
 // query into the gamename and game url into the requests table database
header("Location: ./requestGame.php"); // send the user back to the requestGame page.


?>
