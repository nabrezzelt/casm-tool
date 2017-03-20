<?php
    require_once("includes/init.php");
    require_once("includes/connect.inc.php");

    class Dev
    {
        const RELEASE = 0x0000;
        const SIMPLE  = 0x1000;
        const DEBUG   = 0x2000;
    }

    class AJAXTypes
    {
        const NO_PERMISSION = -1;
        const OK = 0;
        const FAILED = 1;        
    }

    class AutoLoader
    {            
        public static function Init($status)
        {
            switch ($status) 
            {
                case Dev::RELEASE:
                    error_reporting(E_NONE); 
                break;

                case Dev::DEBUG:                                        
                    //error_reporting(E_ALL);

                    ini_set('xdebug.var_display_max_depth', 1023);
                    ini_set('xdebug.var_display_max_children', 256);
                    ini_set('xdebug.var_display_max_data', 1024); 
                break;

                case Dev::SIMPLE:
                    error_reporting(E_ERROR | E_WARNING | E_PARSE);
                break;                
            }

            AutoLoader::Load();           
        }


       private static function Load()
       {
         ### Exceptions
            //require_once("classes/exceptions/SQLException.Class.php");                                
            
         ### Database Classes
            // require_once("classes/Database/exceptions/DatabaseException.php");
            // require_once("classes/Database/Database.Class.php");
            // require_once("classes/Database/MysqlDatabase.Class.php");

         ### Helper Classes
            require_once("classes/Helper.Class.php");
            require_once("classes/Crypt.Class.php");
            require_once("classes/BasicEnum.Class.php");

        ### User/Permission Classes
            require_once("classes/User.Class.php");
            require_once("classes/AssignmentStatus.Class.php");            
            require_once("classes/Permission.Class.php");
            require_once("classes/ExtendedPermission.Class.php");
            require_once("classes/Group.Class.php");   
            require_once("classes/Notification.Class.php");  

        ### CASM-Tool Classes
            require_once("classes/Organisation.Class.php");
            require_once("classes/AssignmentGroup.Class.php");
            require_once("classes/AssignmentSubGroup.Class.php");
            require_once("classes/MenuPoint.Class.php");
            require_once("classes/Role.Class.php");
            require_once("classes/Ausloeser.Class.php");
            require_once("classes/Service.Class.php");            
       }
    }    
?>