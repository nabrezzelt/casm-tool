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
            case "add-menu-point":
                if(isset($_GET['role']) && isset($_GET['menu-point']))
                {
                    echo "s1";
                    if(Permission::hasPermission(Permission::TOOL_CHANGE_MENU_STATE, unserialize($_SESSION['user'])->getID()))
                    {
                        MenuPoint::add(mysql_real_escape_string($_GET['role']), mysql_real_escape_string($_GET['menu-point']));
                        echo "s2";
                    }
                }                
            break;

            case "remove-menu-point":
                if(isset($_GET['role']) && isset($_GET['menu-point']))
                {
                    echo "s3";
                    if(Permission::hasPermission(Permission::TOOL_CHANGE_MENU_STATE, unserialize($_SESSION['user'])->getID()))
                    {
                        MenuPoint::remove(mysql_real_escape_string($_GET['role']), mysql_real_escape_string($_GET['menu-point']));
                        echo "s4";
                    }
                }
            break;

            case "menu-point-delete":
                if(isset($_GET['menu-point']))
                {
                    if(Permission::hasPermission(Permission::TOOL_DELETE_MENU_POINT, unserialize($_SESSION['user'])->getID()))
                    {
                        MenuPoint::delete(mysql_real_escape_string($_GET['menu-point']));
                    }
                    else
                    {
                        return Helper::noPermission(Permission::TOOL_DELETE_MENU_POINT);
                    }
                }
            break;
            

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
