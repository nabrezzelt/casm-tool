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
                                            <a data-toggle='collapse' href='#organisation-" . Helper::formatURL($organisation->getName()) . "-" . $organisation->getID() . "'>" . $organisation->getName() . "</a>                                                                
                                        </h4>
                                    </div>
                                    <div id='organisation-" . Helper::formatURL($organisation->getName()) . "-" . $organisation->getID() . "' class='panel-collapse collapse'>
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
                                                                <a class='btn btn-default btn-xs' href='#'><span class='glyphicon glyphicon-pencil'></span> Edit</a>
                                                                <a class='btn btn-default btn-xs' href='#'><span class='glyphicon glyphicon-trash'></span> Delete</a>
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
                                                                    <a class='btn btn-default btn-xs' href='#'><span class='glyphicon glyphicon-pencil'></span> Edit</a>
                                                                    <a class='btn btn-default btn-xs' href='#'><span class='glyphicon glyphicon-trash'></span> Delete</a>
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
                        <div class='col-sm-4'>
                            <div class='panel panel-default'>
                                <div class='panel-heading'>Create Assignment-Group</div>
                                <div class='panel-body'>
                                     <div class='form-group'>
                                        <label for='sel1'>Select list:</label>
                                        <select class='form-control' id='sel1'>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                        </select>
                                    </div>
                                    <div class='form-group'>
                                        <label for='usr'>Name:</label>
                                        <input type='text' class='form-control' id='usr'>
                                    </div>                                                                                                        
                                </div>
                            </div>
                            <div class='panel panel-default'>
                                <div class='panel-heading'>Create Assignment-Sub-Group</div>
                                <div class='panel-body'>
                                     <div class='form-group'>
                                        <label for='sel1'>Select list:</label>
                                        <select class='form-control' id='sel1'>
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                        </select>
                                    </div>
                                    <div class='form-group'>
                                        <label for='usr'>Name:</label>
                                        <input type='text' class='form-control' id='usr'>
                                    </div>                                                                                                        
                                </div>
                            </div>
                        </div>
                    </div>";                 
                }
                else
                {

                }
                

            break;
        }

        return $re;
    }
?>