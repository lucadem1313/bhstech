<?php
    session_start();
    if(isset($_SESSION["loggedin"]))
    {
        unset($_SESSION["loggedin"]);
    }
    
    if(isset($_COOKIE["loggedin"]))
    {
        unset($_COOKIE['loggedin']);
        setcookie('loggedin', null, -1, '/');
    }
    
    header("Location: ../");
?>