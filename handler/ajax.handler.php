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

        switch ($act)
        {
            case 'getGroup':
                if(isset($_GET['id']))
                {
                    if(Permission::hasPermission(Permission::TOOL_VIEW_ASSIGNMENT_GROUPS, unserialize($_SESSION['user'])->getID()))
                    {
                        $group = AssignmentGroup::getGroupByID($_GET['id']);
                        $json = array('AJAXCode' => AJAXTypes::OK, 'description' => $group->getDescription(), 'statusID' => $group->getStatus()->getID(), 'statusName' => $group->getStatus()->getName());

                        return json_encode($json);
                    }
                    else
                    {
                        $perm = Permission::getPermissionByID(Permission::TOOL_VIEW_ASSIGNMENT_GROUPS);
                        $json = array('AJAXCode' => AJAXTypes::NO_PERMISSION, 'permissionID' => $perm->getID(), 'errorCode' => $perm->getErrorCode(), 'errorMessage' => $perm->getErrorMessage());

                        return json_encode($json);
                    }
                }
                break;
            
            case "getSubGroup":
                if(isset($_GET['id']))
                {
                    if(Permission::hasPermission(Permission::TOOL_VIEW_ASSIGNMENT_GROUPS, unserialize($_SESSION['user'])->getID()))
                    {
                        $group = AssignmentSubGroup::getGroupByID($_GET['id']);
                        $json = array('AJAXCode' => AJAXTypes::OK, 'description' => $group->getDescription(), 'statusID' => $group->getStatus()->getID(), 'statusName' => $group->getStatus()->getName());

                        return json_encode($json);
                    }
                    else
                    {
                        $perm = Permission::getPermissionByID(Permission::TOOL_VIEW_ASSIGNMENT_GROUPS);
                        $json = array('AJAXCode' => AJAXTypes::NO_PERMISSION, 'permissionID' => $perm->getID(), 'errorCode' => $perm->getErrorCode(), 'errorMessage' => $perm->getErrorMessage());

                        return json_encode($json);
                    }
                }
                break;

            default:    
                return json_encode(array('AJAXCode' => AJAXTypes::FAILED));           
                break;
        }
    }

?>