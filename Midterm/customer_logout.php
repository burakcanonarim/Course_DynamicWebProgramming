<?php
    session_start();

    $_SESSION = array();
    
    session_destroy();
    header("location: customer_login.php");
?>