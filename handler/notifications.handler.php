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
            
            case "show":
                if(isset($_GET['id']))
                {
                    $notification = Notification::getNotification($_GET['id']); 
                    if($notification->getUser()->getID() == unserialize($_SESSION['user'])->getID())
                    {
                        Notification::markAsRead($_GET['id']);

                        $re .= "<ul class='breadcrumb admin-panel-header'>
                                    <li><a class='btn btn-default admin-panel-btn-back' href='notifications.php'><span class='glyphicon glyphicon-chevron-left'></span> Notifications</a></li>
                                    <li>" . $notification->getTitle() . "</li>                                            
                                </ul>";
                        $re .= "<hr>";
                        $re .= "<div class='well'>";
                        $re .= $notification->getContent();
                        $re .= "</div>";                        
                    }
                }   
            break;
            
            case "delete":
                if(isset($_GET['id']))
                {
                    if(Notification::isOwner($_GET['id']))
                    {
                        Notification::delete($_GET['id']);
                        Helper::showAlert("Notification sucessfully deleted!", "success");
                        Helper::redirectTo("notifications.php", 3);
                    }
                }
            break;

            default:
                $notifications = Notification::getAllNotifications();

                $re .= "<h3>Neue Benachrichtigungen:</h3>";
                $re .= "<table class='table table-bordered'>";

                if($notifications->count() < 1)
                {
                    $re .= "<tr><td class='text-center'>Keine neuen Benachrichtigungen</td></tr>";
                }
            
                $notifications->setIteratorMode(SplDoublyLinkedList::IT_MODE_FIFO);
                for ($notifications->rewind(); $notifications->valid(); $notifications->next())
                {
                    $notification = $notifications->current();

                    if(!$notification->isRead())
                    {
                        $re .= "<tr><td><a href='notifications.php?act=show&id=" . $notification->getID() . "'><span class=' glyphicon glyphicon-chevron-right'></span> <strong>" . $notification->getTitle() . "</strong></a></td><td class='text-muted'>" . substr($notification->getContent(), 0, 100) . "...</td><td class='col-sm-2'>" . $notification->getCreateDate() . "</td><td class='col-sm-1'><a class='btn btn-default btn-xs' href='notifications.php?act=delete&id=" . $notification->getID() . "'>Delete</a></td></tr>";
                    }
                    else
                    {
                        $re .= "<tr><td><a href='notifications.php?act=show&id=" . $notification->getID() . "'></span> " . $notification->getTitle() . "</a></td><td class='text-muted'>" . substr($notification->getContent(), 0, 100) . "...</td><td class='col-sm-2'>" . $notification->getCreateDate() . "</td><td class='col-sm-1'><a class='btn btn-default btn-xs' href='notifications.php?act=delete&id=" . $notification->getID() . "'>Delete</a></td></tr>";
                    }

                    
                }

                $re .= "</table>";
            break;
        }

        return $re;
    }
?>
