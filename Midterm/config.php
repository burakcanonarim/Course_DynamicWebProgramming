<?php

    $db_servername = "localhost";
    $db_name = "myshop";
    $db_username = "root";
    $db_password = "";

    try{
        $connection = new PDO("mysql:host=$db_servername;dbname=$db_name", $db_username, $db_password);
        $connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e){
        echo "ERROR: Connection is failed!" . $e->getMessage();
    }
?>