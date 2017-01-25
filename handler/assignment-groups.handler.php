<?php
    require_once("includes/connect.inc.php");
    require_once("includes/Autoloader.Class.php");
    Autoloader::Init(Dev::DEBUG);

    function handler()
    {
        $re = "";
        if(isset($_GET['act'])) 
        {
            $act = $_GET['act'];
        }
        else
        {
            $act = "default";
        }
        
        switch ($act) {            
            
            default:
                if(Permission::hasPermission(Permission::ADMIN_REMOVE_USER_FROM_GROUP, unserialize($_SESSION['user'])->getID()))
                {
                    $organisations = Organisation::getAllOrganisations();
                    
                }
                else
                {

                }
                

            break;
        }

        return $re;
    }
?>