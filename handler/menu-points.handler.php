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
                if(Permission::hasPermission(Permission::TOOL_VIEW_MENU_POINTS, unserialize($_SESSION['user'])->getID()))
                {
                    $re .= "<table class='table table-borderd'>";
                    $re .= "<tr>";
                    $re .= "<th>MenuPoint</th>";

                    $roles = Role::getAllRoles();
                    $roles->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                    for ($roles->rewind(); $roles->valid(); $roles->next())
                    {
                        $role = $roles->current();
                        $re .= "<th>" . $role->getName() . "</th>";
                    }

                    $re .= "</tr>";
                    $re .= MenuPoint::printChildsAsList(null, -20);
                    $re .= "</table>";
                }
                else
                {
                    return Helper::noPermission(Permission::TOOL_VIEW_MENU_POINTS);
                }
            break;
        }

        return $re;
    }
?>
