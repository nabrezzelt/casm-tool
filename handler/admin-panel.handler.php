<?php
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
            case 'show-user':
                if(isset($_GET['userID'])) 
                {
                    if(Permission::hasPermission(Permission::ADMIN_VIEW_USER_DETAILS, unserialize($_SESSION['user'])->getID()))
                    {
                        $user = User::getUser((int) $_GET['userID']);

                        $re .= "<div class='panel panel-default'>
                                    <div class='panel-heading'>
                                        <ul class='breadcrumb admin-panel-header'>
                                            <li><a class='btn btn-default admin-panel-btn-back' href='admin-panel.php#userList'><span class='glyphicon glyphicon-chevron-left'></span> Admin-Panel</a></li>
                                            <li>Userdetails</li>                                            
                                        </ul>                                        
                                    </div>
                                    <div class='panel-body'>
                                       <div class='col-sm-4 text-center'>
                                            <h3>" . $user->getUsername() . "</h3>                                            
                                            <hr>                                        
                                            <h5><small>Rankname</small> " . $user->getRankName() . "</h5>
                                            <h5><small>E-Mail</small> " . $user->getEMail() . "</h5>
                                            <h5><small>Last Login</small> " . $user->getLastLogin() . "</h5>
                                            <h5><small>Last IP</small> " . $user->getLastIP() . "</h5>
                                            <h5><small>Register Date</small> " . $user->getRegisterDate() . "</h5>                                                                              
                                        </div> 
                                        <div class='col-sm-3'>";
                                            if(Permission::hasPermission(Permission::ADMIN_VIEW_USER_GROUPS, unserialize($_SESSION['user'])->getID())) 
                                            {
                                                $re .= "<h4>Groups:</h4>
                                                        <table class='table table-bordered'>";

                                                            $groups = Group::getGroupsFromUser($user->getID());
                                                            $groups->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                                                            for ($groups->rewind(); $groups->valid(); $groups->next())
                                                            {
                                                                $group = $groups->current();
                                                                $re .= "<tr>
                                                                            <td class='col-sm-10'>" . $group->getName() . "</td>
                                                                            <td class='text-right col-sm-2'>
                                                                                <a class='btn btn-default btn-xs' href='admin-panel.php?act=remove-group-from-user&groupID=" . $group->getID() . "&userID=" . $user->getID() . "'><span class='glyphicon glyphicon-remove-circle'></span></a>                                                                               
                                                                            </td>
                                                                        </tr>";
                                                            }
                                                            if(Permission::hasPermission(Permission::ADMIN_ADD_USER_TO_GROUP, unserialize($_SESSION['user'])->getID()))
                                                            {
                                                                $re .= "<tr>
                                                                            <td colspan='2'>
                                                                                <form action='admin-panel.php?act=add-user-to-group&userID=" . $user->getID() . "' method='POST'>
                                                                                    <div class='form-group'>
                                                                                        <label>Add Group:</label> 
                                                                                        <select id='group' name='groupID' class='form-control'>";

                                                                                        $groups = Group::getUnassignedGroups($user->getID());
                                                                                        $groups->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                                                                                        for ($groups->rewind(); $groups->valid(); $groups->next())
                                                                                        {
                                                                                            $group = $groups->current();
                                                                                            $re .= "<option value='" . $group->getID() . "'>" . $group->getName() . "</option>";
                                                                                        }

                                                                                $re .= "</select>
                                                                                    </div>
                                                                                    <input class='btn btn-default btn-xs' type='submit' value='Add'/>
                                                                                </form>
                                                                            </td>
                                                                        </tr>";
                                                            }
                                                            else
                                                            {
                                                                 //$re .= Helper::noPermission(Permission::ADMIN_ADD_USER_TO_GROUP);
                                                            }                                                    
                                                $re .= "</table>";
                                            }
                                            else
                                            {
                                                $re .= Helper::noPermission(Permission::ADMIN_VIEW_USER_GROUPS);
                                            }                                                                                
                                $re .= "</div> 
                                        <div class='col-sm-5'>
                                            <div class='panel-group'>
                                                <div class='panel panel-default'>
                                                    <div class='panel-heading'>
                                                        <h4 class='panel-title'>
                                                            <a data-toggle='collapse' href='#collapse-user-permissions'>User Permissions</a>
                                                        </h4>
                                                    </div>
                                                    <div id='collapse-user-permissions' class='panel-collapse collapse in'>
                                                        <div class='panel-body'>
                                                            " . listUserPermissions($user->getID()) . "
                                                        </div>                                                
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                       
                                    </div>
                                </div>";
                    }
                    else
                    {
                        return Helper::noPermission(Permission::ADMIN_VIEW_USER_DETAILS);
                    }
                }                    
            break;            

            case "add-user-to-group":
                if(isset($_POST['groupID']) && isset($_GET['userID'])) 
                {
                    
                    if(Permission::hasPermission(Permission::ADMIN_ADD_USER_TO_GROUP, unserialize($_SESSION['user'])->getID())) 
                    {
                        Group::addUser(mysql_real_escape_string($_GET['userID']), mysql_real_escape_string($_POST['groupID']));
                        
                        Helper::showAlert("<strong>Success!</strong> User successfully added!", "success");                        
                        Helper::redirectTo("admin-panel.php?act=show-user&userID=" . $_GET['userID'], 3);
                    }
                    else
                    {
                        return Helper::noPermission(Permission::ADMIN_ADD_USER_TO_GROUP);
                    }
                }
                else
                {                                   
                    Helper::redirectTo("admin-panel.php?act=show-user&userID=" . $_GET['userID']);
                }
            break;


            case "remove-group-from-user":
                if(isset($_GET['groupID']) && isset($_GET['userID'])) 
                {                    
                    if(Permission::hasPermission(Permission::ADMIN_REMOVE_USER_FROM_GROUP, unserialize($_SESSION['user'])->getID())) 
                    {
                        Group::removeUser(mysql_real_escape_string($_GET['userID']), mysql_real_escape_string($_GET['groupID']));                        
                        Helper::redirectTo("admin-panel.php?act=show-user&userID=" . $_GET['userID']);
                    }
                    else
                    {
                        return Helper::noPermission(Permission::ADMIN_REMOVE_USER_FROM_GROUP);
                    }
                }
                else
                {                                   
                    Helper::redirectTo("admin-panel.php?act=show-user&userID=" . $_GET['userID']);
                }
            break;

            case "save-user-permissions":
                if (Permission::hasPermission(Permission::ADMIN_CHANGE_USER_PERMISSIONS, unserialize($_SESSION['user'])->getID())) 
                {
                    if (isset($_GET['userID'])) 
                    {                                                
                        $ids = array();
                        $i = 0;
                        foreach ($_POST as $key => $value) {
                            $ids[$i] = mysql_real_escape_string($key); 
                            $i++;                            
                        }

                        Permission::saveUserPermissions(mysql_real_escape_string($_GET['userID']), $ids);
                        
                        Helper::showAlert("<strong>Success!</strong> Permission successufully saved.", "success");
                        Helper::redirectTo("admin-panel.php?act=show-user&userID=". $_GET['userID'], 3);                                                                        
                    }
                }
                else
                {
                    return Helper::noPermission(Permission::ADMIN_CHANGE_USER_PERMISSIONS);
                }
            break;

            case "save-group-permissions":
                if (Permission::hasPermission(Permission::ADMIN_CHANGE_GROUP_PERMISSIONS, unserialize($_SESSION['user'])->getID())) 
                {
                    if (isset($_GET['groupID'])) 
                    {                                                
                        $ids = array();
                        $i = 0;
                        foreach ($_POST as $key => $value) {
                            $ids[$i] = mysql_real_escape_string($key); 
                            $i++;                            
                        }

                        Permission::saveGroupPermissions(mysql_real_escape_string($_GET['groupID']), $ids);
                        
                        Helper::showAlert("<strong>Success!</strong> Permissions successufully saved.", "success");
                        Helper::redirectTo("admin-panel.php?act=show-group&groupID=". $_GET['groupID'], 3);                                                                        
                    }
                }
                else
                {
                    return Helper::noPermission(Permission::ADMIN_CHANGE_GROUP_PERMISSIONS);
                }
            break;

            case "show-group":
                if(isset($_GET['groupID'])) 
                {
                    if(Permission::hasPermission(Permission::ADMIN_VIEW_GROUP_DETAILS, unserialize($_SESSION['user'])->getID()))
                    {      
                        $group = Group::getGroupByID($_GET['groupID']);

                        $re .= "<div class='panel panel-default'>
                                    <div class='panel-heading'>
                                        <ul class='breadcrumb admin-panel-header'>
                                            <li><a class='btn btn-default admin-panel-btn-back' href='admin-panel.php#groupManagement'><span class='glyphicon glyphicon-chevron-left'></span> Admin-Panel</a></li>
                                            <li>Groupdetails</li>                                            
                                        </ul>                                        
                                    </div>
                                    <div class='panel-body'>
                                        <div class='row'>
                                            <div class='col-sm-4'>
                                                <h4><small>Group:</small> " . $group->getName() . "</h4>
                                                <hr/>
                                                <div class='panel-group'>
                                                    <div class='panel panel-default'>
                                                        <div class='panel-heading'>
                                                        <h4 class='panel-title'>
                                                            <a data-toggle='collapse' href='#groupMembers'>Members (" . ((Permission::hasPermission(Permission::ADMIN_VIEW_GROUP_MEMBERS, unserialize($_SESSION['user'])->getID())) ? $group->getMemberCount() : '-') . ")</a>
                                                        </h4>
                                                        </div>
                                                        <div id='groupMembers' class='panel-collapse collapse'>
                                                        <div class='panel-body'>" . listGroupMembers($group->getID()) . "</div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='col-sm-8'>                                                
                                                <div class='panel-group'>
                                                    <div class='panel panel-default'>
                                                        <div class='panel-heading'>
                                                        <h4 class='panel-title'>
                                                            <a data-toggle='collapse' href='#groupPermissions'>Group-Permissions</a>
                                                        </h4>
                                                        </div>
                                                        <div id='groupPermissions' class='panel-collapse collapse in'>
                                                        <div class='panel-body'>" . listGroupPermissions($group->getID()) . "</div>
                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>    
                                    </div>
                                </div>";                                  
                    }
                    else
                    {
                        return Helper::noPermission(Permission::ADMIN_VIEW_GROUP_DETAILS);
                    }
                }                           
            break;

            case "group-create":
                if(Permission::hasPermission(Permission::ADMIN_DELETE_GROUP, unserialize($_SESSION['user'])->getID())) 
                {
                    if(isset($_POST['groupName'])) 
                    {
                        Group::create(mysql_real_escape_string($_POST['groupName']));
                        Helper::showAlert("Group successfully created!", "success");
                        Helper::redirectTo("admin-panel.php#groupManagement", 3);
                    } 
                    else
                    {
                        $re .= "<div class='container' style='margin-top: 60px;'>";
                            $re .= "<div class='panel panel-default'>";
                                $re .= "<div class='panel-heading'>";
                                    $re .= "<form action='admin-panel.php?act=group-create' method='POST'>
                                                <div class='form-group'>
                                                    <label for='groupName'>Groupname:</label>
                                                    <input type='text' class='form-control' name='groupName' />
                                                </div>                                        
                                                <button type='submit' class='btn btn-default'>Create</button>
                                            </form>";
                                $re .= "</div>";
                            $re .= "</div>";                                
                        $re .= "</div>";
                    }
                }
                else
                {
                    return Helper::noPermission(Permission::ADMIN_DELETE_GROUP);
                } 
            break;

            case "group-delete":
                if(isset($_GET['groupID'])) 
                {
                    if(Permission::hasPermission(Permission::ADMIN_CREATE_GROUP, unserialize($_SESSION['user'])->getID())) 
                    {
                        Group::delete(mysql_real_escape_string($_GET['groupID']));
                        Helper::showAlert("Group successfully deleted!", "success");
                        Helper::redirectTo("admin-panel.php#groupManagement", 3);
                    }
                    else
                    {
                        return Helper::noPermission(Permission::ADMIN_CREATE_GROUP);
                    }
                }   
            break;

            default:                
                $re .= "<div class='panel panel-default'>                    
                            <div class='panel-heading'>
                                <ul class='breadcrumb admin-panel-header'>
                                    <li>Admin-Panel</li>
                                </ul>                                        
                            </div>
                            <div class='panel-body'>
                                <ul class='nav nav-tabs'>
                                    <li class='active'><a data-toggle='tab' href='#userList'><span class='glyphicon glyphicon-user'></span> Userlist " . ((Permission::hasPermission(Permission::ADMIN_VIEW_USERLIST, unserialize($_SESSION['user'])->getID())) ? "<span class='badge'>" . User::getMemberCount() . "</span>" : '') . " </a></li>
                                    <li><a data-toggle='tab' href='#groupManagement'>Grouplist</a></li>
                                    <li><a data-toggle='tab' href='#categoryProjectList'>Project-/Categorymanagement</a>
                                </ul>

                                <div class='tab-content'>                                    
                                    <div id='userList' class='tab-pane fade in active'>
                                        " . userList() . "
                                    </div>
                                    <div id='groupManagement' class='tab-pane fade'>
                                        " . groupManagement() . "
                                    </div>
                                    <div id='categoryProjectList' class='tab-pane fade'>
                                        <h3>Menu 2</h3>
                                        <p>Some content in menu 2.</p>
                                    </div>
                                </div> 
                            </div>   
                        </div>";                             
            break;  
        }        
        return $re;
    }   

    function groupManagement()
    {
        if(Permission::hasPermission(Permission::ADMIN_VIEW_GROUPLIST, unserialize($_SESSION['user'])->getID()))
        {
            $re = "<table class='table table-striped'>
                        <tr><th>#</th><th>Groupname</th><th>Members</th><th></th></tr>";
                        
                        $groups = Group::getAllGroups();
                        $groups->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                        for ($groups->rewind(); $groups->valid(); $groups->next())
                        {
                            $group = $groups->current();

                            $re .= "<tr>                                        
                                        <td>" . $group->getID() . "</td>
                                        <td>" . $group->getName() . "</td>
                                        <td>" . ((Permission::hasPermission(Permission::ADMIN_VIEW_GROUP_MEMBERS, unserialize($_SESSION['user'])->getID())) ? $group->getMemberCount() : '-') . "</td>                                                                           
                                        <td class='text-right'>
                                            " . (Permission::hasPermission(Permission::ADMIN_VIEW_GROUP_DETAILS, unserialize($_SESSION['user'])->getID()) ? "<a class='btn btn-default btn-xs' href='admin-panel.php?act=show-group&groupID=" . $group->getID() . "'><span class='glyphicon glyphicon-info-sign'></span> Info</a>" : "") . "
                                            " . (Permission::hasPermission(Permission::ADMIN_DELETE_GROUP, unserialize($_SESSION['user'])->getID()) ? "<a class='btn btn-default btn-xs' href='admin-panel.php?act=group-delete&groupID=" . $group->getID() . "'><span class='glyphicon glyphicon-trash'></span> Remove</a>" : '') . "
                                            </td>
                                    </tr>";
                        }

                         if(Permission::hasPermission(Permission::ADMIN_CREATE_GROUP, unserialize($_SESSION['user'])->getID()))
                        {
                            $re .= "<tr><td class='text-right' colspan='4'><a class='btn btn-default' href='admin-panel.php?act=group-create'><span class='glyphicon glyphicon-plus'></span> Create new Group</a></td></tr>";
                        }
                        
            $re .= "</table>";
            return $re;
            
        }
        else
        {
            return Helper::noPermission(Permission::ADMIN_VIEW_GROUPLIST);
        }        
    }

    function userList()
    {
        if(Permission::hasPermission(Permission::ADMIN_VIEW_USERLIST, unserialize($_SESSION['user'])->getID())) 
        {
            $re = "    <table class='table table-striped'>
                            <tr><th>#</th><th>Accountname</th><th>Email</th><th>Rank</th><th>Last-Login</th><th></th></tr>";
                            
                            $users = User::getAllUsers();
                            $users->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                            for ($users->rewind(); $users->valid(); $users->next())
                            {
                                $user = $users->current();

                                $re .= "<tr>                                        
                                            <td>" . $user->getID() . "</td>
                                            <td>" . $user->getUsername() . "</td>
                                            <td>" . $user->getEmail() . "</td>
                                            <td>" . $user->getRankname() . "</td>   
                                            <td>" . $user->getLastLogin() . "</td>                                     
                                            <td>
                                                <a class='btn btn-default btn-xs' href='admin-panel.php?act=show-user&userID=" . $user->getID() . "'><span class='glyphicon glyphicon-info-sign'></span> Info</a>
                                            </td>
                                        </tr>";
                            }

                        
                        
            $re .= "</table>";
            return $re;

        }
        else
        {
            return Helper::noPermission(Permission::ADMIN_VIEW_USERLIST);
        }               
    }

    function listGroupPermissions($groupID)
    {
        if(Permission::hasPermission(Permission::ADMIN_VIEW_GROUP_PERMISSIONS, unserialize($_SESSION['user'])->getID()))
        {
            $re = "<form action='admin-panel.php?act=save-group-permissions&groupID=$groupID' method='POST'>";

                $permissions = ExtendedPermission::getAllGroupPermission($groupID);
                $permissions->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);

                for ($permissions->rewind(); $permissions->valid(); $permissions->next())
                {
                    $permission = $permissions->current();

                    $re .= "<div class='checkbox'>
                                <label>
                                <input name='" . $permission->getID() . "' value='1' type='checkbox' " . (($permission->hasPerm()) ? 'checked': '') . ">
                                " . $permission->getName() . " 
                                <span class='text-muted'>(Error-Code: " . $permission->getErrorCode() . ")</span>
                                </label>
                            </div>";
                }

                if(Permission::hasPermission(Permission::ADMIN_CHANGE_GROUP_PERMISSIONS, unserialize($_SESSION['user'])->getID()))
                {
                    $re .= "<button type='submit' class='btn btn-default'>Save</button>";
                }

            $re .= "</form>";

                    
            return $re;
        }
        else
        {
            return Helper::noPermission(Permission::ADMIN_VIEW_GROUP_PERMISSIONS);
        } 
    }
    
    function listGroupMembers($groupID)
    {
        if(Permission::hasPermission(Permission::ADMIN_VIEW_GROUP_MEMBERS, unserialize($_SESSION['user'])->getID()))
        {        
            $re = "<table class='table table-bordered'>";

            $users = Group::getAllUsersFromGroupID($groupID);
            $users->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
            for ($users->rewind(); $users->valid(); $users->next())
            {
                $user = $users->current();

                $re .= "<tr>
                            <td class='col-sm-10'><a href='admin-panel.php?act=show-user&userID=" . $user->getID() . "'>" . $user->getUsername() . "</a></td>
                            <td class='text-right col-sm-2'>
                                <a class='btn btn-default btn-xs' href='admin-panel.php?act=remove-group-from-user&amp;groupID=1&amp;userID=" . $user->getID() . "'>
                                    <span class='glyphicon glyphicon-remove-circle'></span> Remove
                                </a>                                                                               
                            </td>
                        </tr>";
            }
            $re .= "</table>";

            return $re;
        }
        else
        {
            return Helper::noPermission(Permission::ADMIN_VIEW_GROUP_MEMBERS);
        } 
    }

    function listUserPermissions($userID) 
    {
        if(Permission::hasPermission(Permission::ADMIN_VIEW_USER_PERMISSIONS, unserialize($_SESSION['user'])->getID()))
        {
            $re = "<form action='admin-panel.php?act=save-user-permissions&userID=$userID' method='POST'>";

                $permissions = ExtendedPermission::getAllUserPermission($userID);
                $permissions->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);

                for ($permissions->rewind(); $permissions->valid(); $permissions->next())
                {
                    $permission = $permissions->current();

                    $re .= "<div class='checkbox'>
                                <label>
                                <input name='" . $permission->getID() . "' value='1' type='checkbox' " . (($permission->hasPerm()) ? 'checked': '') . ">
                                " . $permission->getName() . " 
                                <span class='text-muted'>(Error-Code: " . $permission->getErrorCode() . ")</span>
                                </label>
                            </div>";
                }

                if(Permission::hasPermission(Permission::ADMIN_CHANGE_USER_PERMISSIONS, unserialize($_SESSION['user'])->getID()))
                {
                    $re .= "<button type='submit' class='btn btn-default'>Save</button>";
                }

            $re .= "</form>";

                    
            return $re;
        }
        else
        {
            return Helper::noPermission(Permission::ADMIN_VIEW_USER_PERMISSIONS);
        } 
    }
    
?>
    