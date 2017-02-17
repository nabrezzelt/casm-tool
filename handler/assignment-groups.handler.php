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

                if(Permission::hasPermission(Permission::TOOL_VIEW_ASSIGNMENT_GROUPS, unserialize($_SESSION['user'])->getID()))
                {
                    $re .= "<h3>Organisations</h3>
                                <div class='row'>
                                    <div class='col-sm-8'>
                                        <div class='panel-group'>";

                    $organisations = Organisation::getAllOrganisations();              
                    $organisations->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                    for ($organisations->rewind(); $organisations->valid(); $organisations->next())
                    {
                        $organisation = $organisations->current();
                        
                        $re .= "<div class='panel panel-default'>
                                    <div class='panel-heading'>
                                        <h4 class='panel-title'>                                           
                                            <a data-toggle='collapse' href='#organisation-" . $organisation->getID() . "'>" . $organisation->getName() . "</a>                                                                
                                        </h4>
                                    </div>
                                    <div id='organisation-" . $organisation->getID() . "' class='panel-collapse collapse'>
                                        <div class='panel-body'>";

                                        if($organisation->getAssignmentGroups()->count() > 0)
                                        {
                                            $re .= "<table class='table table-bordered'>";
                                            $assingnmentGroups = $organisation->getAssignmentGroups();
                                            $assingnmentGroups->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                                            for ($assingnmentGroups->rewind(); $assingnmentGroups->valid(); $assingnmentGroups->next())
                                            {
                                                $assingnmentGroup = $assingnmentGroups->current();

                                                $re .= "<tr>
                                                            <td colspan='2'>" . $assingnmentGroup->getName() . "</td>
                                                            <td class='col-sm-3 text-right'>
                                                                <a class='btn btn-default btn-xs show-group-details' href='assignment-groups.php?act=assignment-group-view&id=" . $assingnmentGroup->getID() . "'><span class='glyphicon glyphicon-info-sign'></span> Info</a>
                                                                <!-- <a class='btn btn-default btn-xs' href=''><span class='glyphicon glyphicon-pencil'></span> Edit</a> -->
                                                                <a class='btn btn-default btn-xs' href='assignment-groups.php?act=delete-group&id=" . $assingnmentGroup->getID() . "'><span class='glyphicon glyphicon-trash'></span> Delete</a>
                                                            </td>
                                                        </tr>";

                                                $assingnmentSubGroups = $assingnmentGroup->getAssignmentSubGroups();
                                                $assingnmentSubGroups->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                                                for ($assingnmentSubGroups->rewind(); $assingnmentSubGroups->valid(); $assingnmentSubGroups->next())
                                                {
                                                    $assingnmentSubGroup = $assingnmentSubGroups->current();
                                                    
                                                    $re .= "<tr>
                                                                <td></td>
                                                                <td>" . $assingnmentSubGroup->getName() . "</td>
                                                                <td class='col-sm-3 text-right'>
                                                                    <a class='btn btn-default btn-xs show-sub-group-details' href='assignment-groups.php?act=assignment-sub-group-view&id=" . $assingnmentSubGroup->getID() . "'><span class='glyphicon glyphicon-info-sign'></span> Info</a>
                                                                    <!-- <a class='btn btn-default btn-xs' href='#'><span class='glyphicon glyphicon-pencil'></span> Edit</a> -->
                                                                    <a class='btn btn-default btn-xs'href='assignment-groups.php?act=delete-sub-group&id=" . $assingnmentSubGroup->getID() . "'><span class='glyphicon glyphicon-trash'></span> Delete</a>
                                                                </td>
                                                                </tr>";
                                                }
                                            }
                                            $re .= "</table>";
                                        }
                                        else
                                        {
                                            $re .= "<p>Keine Untergruppen verfügbar</p>";
                                        }

                                $re .= "</div>
                                    </div>
                                </div>";
                            
                    }   

                    $re .= "</div>
                        </div>
                        <div class='col-sm-4'>";
                            
                            if(Permission::hasPermission(Permission::TOOL_CREATE_ASSIGNMENT_GROUP, unserialize($_SESSION['user'])->getID()))
                            {
                                $re .= "
                                <div class='panel-group' id='accordion'>
                                <div class='panel panel-default'>
                                    <div class='panel-heading'><h4 class='panel-title'><a data-toggle='collapse' data-parent='#accordion' href='#create-group'>Create Assignment-Group</a></h4></div>
                                    <div id='create-group' class='panel-collapse collapse in'>
                                    <div class='panel-body'>
                                        <form action='assignment-groups.php?act=assinment-group-add' method='POST'>
                                            <div class='form-group'>
                                                <label for='sel1'>Organisation:</label>
                                                <select class='form-control' name='organisation' required>";
                                                    
                                                    $organisations = Organisation::getAllOrganisationsWithoutSubGroups();
                                                    $organisations->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                                                    for ($organisations->rewind(); $organisations->valid(); $organisations->next())
                                                    {
                                                        $organisation = $organisations->current();
                                                        $re .= "<option value='" . $organisation->getID() . "'>" . $organisation->getName() . "</option>";
                                                    }

                                        $re .= "</select>
                                            </div>
                                            <div class='form-group'>
                                                <label for='usr'>Name: »</label>
                                                <input type='text' class='form-control' name='assignment-group-name' required>
                                            </div>
                                            <div class='form-group'>
                                                <label for='description'>Description:</label>
                                                <textarea name='description' class='form-control' rows='3'></textarea>
                                            </div>
                                            <div class='form-group'>
                                                <label for='sel1'>Status:</label>
                                                <select class='form-control' name='status' required>";
                                                    
                                                    $status = AssignmentStatus::getAllStatus();
                                                    $status->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                                                    for ($status->rewind(); $status->valid(); $status->next())
                                                    {
                                                        $s = $status->current();
                                                        $re .= "<option value='" . $s->getID() . "'>" . $s->getName() . "</option>";
                                                    }

                                        $re .= "</select>
                                            </div>
                                            <input type='submit' class='btn btn-default' value='Save' />
                                        </form>                                                                                                          
                                    </div>
                                    </div>
                                </div>";
                            }                            

                            if(Permission::hasPermission(Permission::TOOL_CREATE_ASSIGNMENT_SUB_GROUP, unserialize($_SESSION['user'])->getID()))
                            {
                                $re .= "
                                <div class='panel panel-default'>
                                    <div class='panel-heading'>
                                        <h4 class='panel-title'>
                                            <a data-toggle='collapse' data-parent='#accordion' href='#create-sub-group'>Create Assignment-Sub-Group</a>
                                        </h4>
                                    </div>
                                    <div id='create-sub-group' class='panel-collapse collapse'>
                                        <div class='panel-body'>
                                            <form action='assignment-groups.php?act=assignment-sub-group-add' method='POST'>
                                                <div class='form-group'>
                                                    <label for='sel1'>Assignment-Group:</label>
                                                    <select class='form-control' name='assignment-group' required>";
                                                    
                                                    $assingnmentGroups = AssignmentGroup::getAllAssignmentGroupsWithoutSubGroups();
                                                    $assingnmentGroups->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                                                    for ($assingnmentGroups->rewind(); $assingnmentGroups->valid(); $assingnmentGroups->next())
                                                    {
                                                        $assingnmentGroup = $assingnmentGroups->current();
                                                        $re .= "<option value='" . $assingnmentGroup->getID() . "'>" . $assingnmentGroup->getName() . "</option>";
                                                    }

                                        $re .= "</select>
                                                </div>
                                                <div class='form-group'>
                                                    <label for='usr'>Name:</label>
                                                    <input type='text' class='form-control' name='assignment-sub-group-name' required>
                                                </div>
                                                <div class='form-group'>
                                                    <label for='description'>Description:</label>
                                                    <textarea name='description' class='form-control' rows='3'></textarea>
                                                </div>
                                                <div class='form-group'>
                                                    <label for='sel1'>Status:</label>
                                                    <select class='form-control' name='status' required>";
                                                    
                                                    $status = AssignmentStatus::getAllStatus();
                                                    $status->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                                                    for ($status->rewind(); $status->valid(); $status->next())
                                                    {
                                                        $s = $status->current();
                                                        $re .= "<option value='" . $s->getID() . "'>" . $s->getName() . "</option>";
                                                    }

                                        $re .= "</select>
                                                </div>
                                                <input type='submit' class='btn btn-default' value='Save' />
                                            </form>
                                        </div>
                                    </div>
                                </div>";
                            }                            
                $re .= "</div>                
                    </div>
                    </div>";                               
                }
                else
                {
                    return Helper::noPermission(Permission::TOOL_VIEW_ASSIGNMENT_GROUPS);
                }
                

            break;

            case "assinment-group-add":
                if(isset($_POST['organisation']) && isset($_POST['assignment-group-name']) && isset($_POST['status']))
                {
                    if(Permission::hasPermission(Permission::TOOL_CREATE_ASSIGNMENT_GROUP, unserialize($_SESSION['user'])->getID()))
                    {
                        AssignmentGroup::create(
                            mysql_real_escape_string($_POST['organisation']),
                            mysql_real_escape_string($_POST['assignment-group-name']),
                            mysql_real_escape_string($_POST['description']),
                            mysql_real_escape_string($_POST['status']));

                        Helper::redirectTo("assignment-groups.php#organisation-" . $_POST['assignment-group-name']);
                    }
                    else
                    {
                        return Helper::noPermission(Permission::TOOL_CREATE_ASSIGNMENT_GROUP);
                    }
                }
            break;

            case "assignment-sub-group-add":
                if(isset($_POST['assignment-group']) && isset($_POST['assignment-sub-group-name']) && isset($_POST['status']))
                {
                    if(Permission::hasPermission(Permission::TOOL_CREATE_ASSIGNMENT_SUB_GROUP, unserialize($_SESSION['user'])->getID()))
                    {
                        AssignmentSubGroup::create(
                            mysql_real_escape_string($_POST['assignment-group']),
                            mysql_real_escape_string($_POST['assignment-sub-group-name']),
                            mysql_real_escape_string($_POST['description']),
                            mysql_real_escape_string($_POST['status']));
                        Helper::redirectTo("assignment-groups.php");
                    }
                    else
                    {
                        return Helper::noPermission(Permission::TOOL_CREATE_ASSIGNMENT_SUB_GROUP);
                    }
                }
            break;

            case "assignment-group-view":
                if(Permission::hasPermission(Permission::TOOL_VIEW_ASSIGNMENT_GROUPS, unserialize($_SESSION['user'])->getID()))
                {
                    if(isset($_POST['description']))
                    {                   
                        AssignmentGroup::saveDescription($_GET['id'], mysql_real_escape_string($_POST['description']));
                        Helper::redirectTo("assignment-groups.php");
                    }
                    else
                    {
                        $group = AssignmentGroup::getGroupByID($_GET['id']);   
                        $re .= "<h3>Edit:</h3><ul class='breadcrumb'><li><a href='#'>" . Helper::getOrganisationByAssignmentGroup($_GET['id'])->getName() . "</a></li><li class='active'>" . $group->getName() . "</li></ul>";                                                             
                        $re .= "<form action='assignment-groups.php?act=assignment-group-view&id=" . $_GET['id'] . "' method='POST'>
                                <div class='form-group'>
                                    <label for='description'>Description:</label>
                                    <textarea name='description' class='form-control' rows='3'>" . $group->getDescription() . "</textarea>
                                </div>
                                <button style='margin-top: 10px; align: right;' type='submit' class='btn btn-primary'>Save Changes</button>
                            </form>";
                    }
                }                
            break;

            case "assignment-sub-group-view":
                if(Permission::hasPermission(Permission::TOOL_VIEW_ASSIGNMENT_GROUPS, unserialize($_SESSION['user'])->getID()))
                {
                    if(isset($_POST['description']))
                    {                   
                        AssignmentSubGroup::saveDescription($_GET['id'], mysql_real_escape_string($_POST['description']));
                        Helper::redirectTo("assignment-groups.php");
                    }
                    else
                    {
                        $group = AssignmentSubGroup::getGroupByID($_GET['id']);
                        $re .= "<h3>Edit:</h3><ul class='breadcrumb'><li><a href='#'>" . Helper::getOrganisationBySubGroup($_GET['id'])->getName() . "</a></li><li><a href='#'>" . Helper::getParentGroupBySubGroup($_GET['id'])->getName() . "</a></li><li class='active'>" . $group->getName() . "</li></ul>";                    
                        $re .= "<form action='assignment-groups.php?act=assignment-sub-group-view&id=" . $_GET['id'] . "' method='POST'>
                                <div class='form-group'>
                                    <label for='description'>Description:</label>
                                    <textarea name='description' class='form-control' rows='3'>" . $group->getDescription() . "</textarea>
                                </div>
                                <button style='margin-top: 10px; align: right;' type='submit' class='btn btn-primary'>Save Changes</button>
                            </form>";
                    }
                }
            break;
        }

        return $re;
    }
?>
