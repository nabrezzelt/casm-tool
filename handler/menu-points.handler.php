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
                    if(Permission::hasPermission(Permission::TOOL_CHANGE_MENU_STATE, unserialize($_SESSION['user'])->getID()))
                    {
                        MenuPoint::addRolePermission(mysql_real_escape_string($_GET['role']), mysql_real_escape_string($_GET['menu-point']));                        
                    }
                }                
            break;

            case "remove-menu-point":
                if(isset($_GET['role']) && isset($_GET['menu-point']))
                {                
                    if(Permission::hasPermission(Permission::TOOL_CHANGE_MENU_STATE, unserialize($_SESSION['user'])->getID()))
                    {
                        MenuPoint::removeRolePermission(mysql_real_escape_string($_GET['role']), mysql_real_escape_string($_GET['menu-point']));                       
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
            
            case "menu-point-add":
                if(Permission::hasPermission(Permission::TOOL_CREATE_MENU_POINT, unserialize($_SESSION['user'])->getID()))
                {
                    if(isset($_POST['menu-point-name']) && isset($_POST['parent-menu-point'])) 
                    {                        
                        MenuPoint::create(mysql_real_escape_string($_POST['menu-point-name']), mysql_real_escape_string($_POST['parent-menu-point']), mysql_real_escape_string($_POST['menu-point-description']));
                        Helper::showAlert("Menu-Point successfully created!", "success");
                        Helper::redirectTo("menu-points.php", 3);
                    }
                    else
                    {
                        $re .= "<form class='form-horizontal' method='POST'>
                                    <div class='form-group'>
                                        <label class='control-label col-sm-2' for='email'>Übergeortneter Menüpunkt:</label>
                                        <div class='col-sm-10'>
                                            <select class='form-control' name='parent-menu-point'>
                                                <option value='0' selected='selected'>Keiner</option>";
                                                
                                                $menuPoints = MenuPoint::getAllMenuPoints();

                                                $menuPoints->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                                                for ($menuPoints->rewind(); $menuPoints->valid(); $menuPoints->next())
                                                {
                                                    $menuPoint = $menuPoints->current();

                                                    $re .= "<option value='" . $menuPoint->getID() . "'>" . $menuPoint->getName() . "</option>";
                                                }

                                    $re .= "</select>
                                        </div>
                                    </div>
                                    <div class='form-group'>
                                        <label class='control-label col-sm-2'>MenuPoint-Name:</label>
                                        <div class='col-sm-10'>
                                        <input type='text' class='form-control' placeholder='Enter name' name='menu-point-name'>
                                        </div>
                                    </div>  
                                     <div class='form-group'>
                                        <label class='control-label col-sm-2'>MenuPoint-Description:</label>
                                        <div class='col-sm-10'>
                                        <textarea class='form-control' placeholder='Enter description' name='menu-point-description'></textarea>
                                        </div>
                                    </div>                             
                                    <div class='form-group'>
                                        <div class='col-sm-offset-2 col-sm-10'>
                                        <button type='submit' class='btn btn-default'>Create</button>
                                        </div>
                                    </div>
                                </form>";
                    }
                }
                else
                {
                    return Helper::noPermission(Permission::TOOL_CREATE_MENU_POINT);
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

                    $re .= "<th></th></tr>";
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
