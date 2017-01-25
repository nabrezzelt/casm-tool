<?php
    require_once("includes/Autoloader.Class.php");
    Autoloader::Init(Dev::DEBUG); 

    //Online auf Offline setzten
    User::Logout(unserialize($_SESSION['user']));    
    $_SESSION['user'] = "";

    session_destroy();   
    Helper::redirectTo("index.php");
?>