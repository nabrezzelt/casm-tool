<?php
    require_once("includes/connect.inc.php");
    require_once("includes/Autoloader.Class.php");
    Autoloader::load();

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
            break;  
        }

        return $re;
    }

    function listAssignedBugs()
    {
        $re = "";
                        
        if (Permission::hasPermission(Permission::ACCOUNT_VIEW_ASSIGNED_BUGS, unserialize($_SESSION['user'])->getID()) && Permission::hasPermission(Permission::SHOW_BUGLIST, unserialize($_SESSION['user'])->getID())) 
        {     
            $bugs = Bug::getAllBugsAssignedTo(unserialize($_SESSION['user'])->getID());

            $re .= "<div class='panel panel-default'>
                        <div class='panel-body'>
                            <table class='table table-striped display buglist'>
                                <thead>
                                    <tr>
                                        <th>Priorität</th>
                                        <th>ID</th>                                                
                                        <th>Status</th>
                                        <th>Aktualisiert</th>
                                        <th>Zusammenfassung</th>
                                        <th>Reported By</th>
                                    </tr>
                                </thead>
                                <tbody>";

                                    $bugs->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                                    for ($bugs->rewind(); $bugs->valid(); $bugs->next()) 
                                    {
                                        $bug = $bugs->current();
                                       
                                        $statusText = $bug->getStatus()->getName();
                                        if($bug->getStatus()->getID() == Status::ASSIGNED)
                                        {                               
                                            $statusText .=  " (<a href='/devControl/user/" . $bug->getAssignedToUser()->getID() . "/" . Helper::formatURL($bug->getAssignedToUser()->getUsername()) . "'>" . $bug->getAssignedToUser()->getUsername() . "</a>)"; 
                                        } 

                                        $re .= "<tr onClick='showBug(" . $bug->getCategoryID() . "," . $bug->getID() . ");' data-category-id='1' data-bug-id='1'>                                                            
                                                    <td><span class='hiddenSort'>" . $bug->getPriority()->getImportantValue() . "</span> <span class='label label-" . $bug->getPriority()->getColorName() . "'>" . $bug->getPriority()->getName() . "</span></td>
                                                    <td>" . $bug->getID() . "</td>
                                                    <td>$statusText</td>
                                                    <td><span class='hiddenSort'>" . $bug->getCreateTime() . "</span><span data-toggle='tooltip' data-placement='bottom' title='" . $bug->getCreateTime() . "'>" . Helper::humanTiming(strtotime($bug->getCreateTime())) . "</span></td>
                                                    <td>" . $bug->getTitle() . "</td>
                                                    <td><a href='/devControl/user/" . $bug->getUser()->getID() . "/" . Helper::formatURL($bug->getUser()->getUsername()) . "'>" . $bug->getUser()->getUsername() . "</a></td>
                                                </tr>";                                                                                                                               
                                    }
                                                                                
                        $re .= "</tbody>
                            </table>  
                        </div>
                    </div>";
        }
        else 
        {
            $re .= Helper::noPermission(Permission::SHOW_BUGLIST);
            $re .= "or<br/><br/>";
            $re .= Helper::noPermission(Permission::ACCOUNT_VIEW_ASSIGNED_BUGS);
        }    

        return $re;                
    }

    function listOpenedBugs()
    {
        $re = "";                      

        if (Permission::hasPermission(Permission::ACCOUNT_VIEW_OPENED_BUGS, unserialize($_SESSION['user'])->getID()) && Permission::hasPermission(Permission::SHOW_BUGLIST, unserialize($_SESSION['user'])->getID())) 
        {        
            $bugs = Bug::getAllCreatedBugsByUserID(unserialize($_SESSION['user'])->getID()); 

            $re .= "<div class='panel panel-default'>
                        <div class='panel-body'>
                            <table class='table table-striped display buglist'>
                                <thead>
                                    <tr>
                                        <th>Priorität</th>
                                        <th>ID</th>                                                
                                        <th>Status</th>
                                        <th>Aktualisiert</th>
                                        <th>Zusammenfassung</th>
                                        <th>Reported By</th>
                                    </tr>
                                </thead>
                                <tbody>";

                                    $bugs->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                                    for ($bugs->rewind(); $bugs->valid(); $bugs->next()) 
                                    {
                                        $bug = $bugs->current();
                                       
                                        $statusText = $bug->getStatus()->getName();
                                        if($bug->getStatus()->getID() == Status::ASSIGNED)
                                        {                               
                                            $statusText .=  " (<a href='/devControl/user/" . $bug->getAssignedToUser()->getID() . "/" . Helper::formatURL($bug->getAssignedToUser()->getUsername()) . "'>" . $bug->getAssignedToUser()->getUsername() . "</a>)"; 
                                        } 

                                        $re .= "<tr onClick='showBug(" . $bug->getCategoryID() . "," . $bug->getID() . ");' data-category-id='1' data-bug-id='1'>                                                            
                                                    <td><span class='hiddenSort'>" . $bug->getPriority()->getImportantValue() . "</span> <span class='label label-" . $bug->getPriority()->getColorName() . "'>" . $bug->getPriority()->getName() . "</span></td>
                                                    <td>" . $bug->getID() . "</td>
                                                    <td>$statusText</td>
                                                    <td><span class='hiddenSort'>" . $bug->getCreateTime() . "</span><span data-toggle='tooltip' data-placement='bottom' title='" . $bug->getCreateTime() . "'>" . Helper::humanTiming(strtotime($bug->getCreateTime())) . "</span></td>
                                                    <td>" . $bug->getTitle() . "</td>
                                                    <td><a href='/devControl/user/" . $bug->getUser()->getID() . "/" . Helper::formatURL($bug->getUser()->getUsername()) . "'>" . $bug->getUser()->getUsername() . "</a></td>
                                                </tr>";                                                                                                                              
                                    }
                                                                                
                        $re .= "</tbody>
                            </table>  
                        </div>
                    </div>";
        }
        else 
        {
            $re .= Helper::noPermission(Permission::SHOW_BUGLIST);
            $re .= "or<br/><br/>";
            $re .= Helper::noPermission(Permission::ACCOUNT_VIEW_ASSIGNED_BUGS);
        }    

        return $re;          
    }
?>
    