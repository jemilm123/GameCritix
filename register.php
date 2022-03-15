<?php
session_start(); // starts php session which is used for user authorization


$mysqli = new mysqli("localhost","hsehra","cuWraved","hsehra"); // creates a new connection to the mysql database

if ($mysqli -> connect_errno){ // check if mysql encountered an error, if so exit
    echo "Failed to connect to MySQL: " . $mysqli -> connect_errno;
    exit();
}

if (!isset($_POST['username']) || !isset($_POST['password']) ){ // if the username and password parameters arent set
    setcookie("message", "Registration Failed" . $_POST["username"] . $_POST["password"] , time() + (30), "/"); // set message that registration failed
    header("Location: ./registerPage.php"); // send user back to the registration page
    exit();
}
$user = $_POST["username"];
$pass = password_hash($_POST["password"],PASSWORD_DEFAULT);
// stores the posted parameters to their corresponding variables, and securely hashes the password
unset($_POST["username"]);
unset($_POST["password"]);

$commandText = "INSERT INTO login (username,password) VALUES ('".$user."','".$pass."')";

$result = $mysqli->query($commandText);

// queries the user's information into the database
if($result){ // if account created successfully
    setcookie("message", "Account Created", time() + (30), "/"); // set message accordingly
    header("Location: ./loginPage.php"); // send user to the login page
    exit();
}
else{ // if account didnt get created, username was taken already
    setcookie("message", "ERROR, Username Already Taken", time() + (30), "/"); // set message accordingly
    header("Location: ./registerPage.php"); // send user back to registration
    exit();
}

?>