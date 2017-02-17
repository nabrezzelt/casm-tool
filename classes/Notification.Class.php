<?php
    /**
     * 
     */
    class Notification 
    {
        private
            $id,
            $user,
            $title,
            $content,
            $read,
            $createTime;

        function __construct($id, $user, $title, $content, $read, $createTime)
        {
            $this->id = $id;
            $this->user = $user;
            $this->title = $title;
            $this->content = $content;
            $this->read = (bool) $read;
            $this->createTime = $createTime; 
        }       

        public function getID()
        {
            return $this->id;            
        }

        public function getTitle()
        {
            return $this->title;
        }

        public function getContent()
        {
            return $this->content;
        }

        public function getUser()
        {
            return $this->user;
        }

        public function getCreateDate()
        {
            return $this->createTime;
        }

        public function isRead()
        {
            return $this->read;
        }

        public static function getAllUnreadNotifications()
        {
            $query = "SELECT * FROM notifications WHERE readByUser = 0 AND userID = " . unserialize($_SESSION['user'])->getID();            
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));

            $notifications = new SplDoublyLinkedList();

            while($row = mysql_fetch_assoc($res))
            {
                $id = $row['id'];
                $user = User::getUser((int) $row['userID']);
                $title = $row['title'];                
                $content = $row['content'];
                $read = false;
                $createTime = $row['createTime'];

                $notifications->push(new Notification($id, $user, $title, $content, $read, $createTime));
            }

            return $notifications;
        }

        public static function getAllNotifications()
        {
            $query = "SELECT * FROM notifications WHERE userID = " . unserialize($_SESSION['user'])->getID();            
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));

            $notifications = new SplDoublyLinkedList();

            while($row = mysql_fetch_assoc($res))
            {
                $id = $row['id'];
                $user = User::getUser((int) $row['userID']);
                $title = $row['title'];                
                $content = $row['content'];
                $read = $row['readByUser'];
                $createTime = $row['createTime'];

                $notifications->push(new Notification($id, $user, $title, $content, $read, $createTime));
            }

            return $notifications;
        }

        public static function markAsRead($notificationID)
        {
            $query = "UPDATE notifications SET readByUser = 1 WHERE id = $notificationID";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }  

        public static function getNotification($notificationID)
        {
            $query = "SELECT * FROM notifications WHERE id = $notificationID";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
            $row = mysql_fetch_assoc($res);

            $id = $row['id'];
            $user = User::getUser((int) $row['userID']);
            $title = $row['title'];                
            $content = $row['content'];
            $read = false;
            $createTime = $row['createTime'];

            return new Notification($id, $user, $title, $content, $read, $createTime);        
        }  

        public static function sendNotificationTo($userID, $title, $content)
        {
            $query = "INSERT INTO notifications (userID, title, content) VALUES ($userID, '$title', '$content')";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }    
    }
    
?>