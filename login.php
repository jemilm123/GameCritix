<?php
session_start();
// starts php session which is used for user authorization
$mysqli = new mysqli("localhost","hsehra","cuWraved","hsehra"); // creates a new connection to the mysql database

if ($mysqli -> connect_errno){ // check if mysql encountered an error, if so exit
    echo "Failed to connect to MySQL: " . $mysqli -> connect_errno;
    exit();
}

function invalidErr(){ // General function for when an invalid parameter is given or error occurs
    setcookie("message", "Invalid User/Pass", time() + (30), "/");
    header("Location: ./loginPage.php");
    exit();
}


if (!isset($_POST['username']) || !isset($_POST['password']) ){ // trigger the error function if username and password are not posted
    invalidErr();
}

$commandText = "select * from login where username='".$_POST['username']."'";

$result = $mysqli->query($commandText);
// queries the database for a user with the given username
if($result){ // if a user with given username is found
    $row = mysqli_fetch_assoc($result);
    if (password_verify($_POST["password"], $row["password"])) { // if the given password doesnt match the hashed password
        $_SESSION['user_id'] = $row["id"]; // set session user_id to the user's id and authorize them
        setcookie("message", "Login Successful", time() + (30), "/"); // creates a cookie to hold a message of successful login
    }
    else{ // password was incorrect
        invalidErr();
    }
}
else{ // user not found in the database
    invalidErr();
}

header("Location: ./home.php"); // if login occurred without problems, send the user to the home page.
exit();

?>