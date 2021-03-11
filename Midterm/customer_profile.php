<?php
    require_once "config.php";

    session_start();
    
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: customer_login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html>

<head>
    <title> Profile Page </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">

</head>
<style>
    body{
        font: 14px sans-serif; text-align: center;
    }
    .welcome-message{
        font-size: 30px;
        background-color:#353635;
        padding: 40px 0;
        font-family: Bahnschrift;
        color: #f9d423;
    }
</style>

<body>
    <nav class="welcome-message">
    Your id is: <b><i><u><?php echo htmlspecialchars($_SESSION["id"]); ?></u></i></b><br>
    Your username is: <b><i><u><?php echo htmlspecialchars($_SESSION["username"]); ?></u></i></b><br>
    Your e-mail is: <b><i><u><?php echo htmlspecialchars($_SESSION["email"]); ?></u></i></b><br><br>
    <a href="customer_changeEmail.php" class="btn btn-danger">Change e-Mail</a>
    <a href="customer_changeUsername.php" class="btn btn-danger">Change Username</a>
    <a href="customer_changePassword.php" class="btn btn-danger">Change password</a><br>
    <a href="customer_welcome.php" class="btn btn-danger">Go back!</a>

        </nav>
