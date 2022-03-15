<?php
session_start(); // starts php session which is used for user authorization

session_destroy(); // stops the php session and thereby logging out the user
header("Location: ./home.php"); // sends the user back home
?>