<?php        
    require_once("includes/Autoloader.Class.php");
    require_once("handler/ajax.handler.php");
    Autoloader::Init(Dev::DEBUG);    

    if (!isset($_SESSION['user'])) 
    {        
        //User is not LoggedIn
        echo json_encode(array('AJAXCode' => AJAXTypes::FAILED));       
    }

    echo handler();






?>