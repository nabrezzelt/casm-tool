<?php
    /**
     * 
     */
    class Group
    {
        private $id;
        private $name;    

        function __construct($id, $name)
        {
            $this->id= $id;
            $this->name = $name;           
        }

        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        public function setName($name)
        {
            $this->name = $name;
        }

        public function getName()
        {
            return $this->name;
        }        

        public function getMemberCount()
        {
            $query = "SELECT count(*) AS memberCount FROM group_user_relation WHERE groupID = " . $this->id;
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__)); 
            $row = mysql_fetch_assoc($res);

            return $row['memberCount'];       
        }

        public function getGroupByID($groupID)
        {
            $query = "SELECT * FROM groups WHERE id = $groupID";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
            $row = mysql_fetch_assoc($res);
            
            $id = $row['id'];
            $name = $row['name'];
            
            return new Group($id, $name);

        }

        public static function create($groupName)
        {
            $query = "INSERT INTO groups (name) VALUES ('$groupName')";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));           
        }

        public static function delete($groupID)
        {
            Group::removeAllUsers($groupID);
            Group::removeAllPermissions($groupID);
            $query = "DELETE FROM groups WHERE id = $groupID";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__)); 
        }

        public static function removeAllUsers($groupID)
        {
            $query = "DELETE FROM group_user_relation WHERE groupID = $groupID";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__)); 
        }

        public static function removeAllPermissions($groupID)
        {
            $query = "DELETE FROM group_permissions WHERE groupID = $groupID";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__)); 
        }

        public static function addUser($userID, $groupID)
        {
            $query = "INSERT INTO group_user_relation (userID, groupID) VALUES ($userID, $groupID)";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }  

        public static function removeUser($userID, $groupID)
        {
            $query = "DELETE FROM group_user_relation WHERE userID = $userID AND groupID = $groupID";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }

        public static function getGroupsFromUser($userID)
        {
            $query = "SELECT * 
                      FROM group_user_relation 
                      JOIN groups
                      ON group_user_relation.groupID = groups.id
                      WHERE userID = '$userID'";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));

            $groups = new SplDoublyLinkedList();

            while($row = mysql_fetch_assoc($res))
            {
                $id = $row['id'];
                $name = $row['name'];
                
                $groups->push(new Group($id, $name));
            }

            return $groups;
        }  

        public static function getUnassignedGroups($userID)
        {
            $query = "SELECT id, name, IF(userID = $userID, 1, 0)
                      FROM groups                   
                      LEFT JOIN group_user_relation 
                      ON groups.id = group_user_relation.groupID
                      WHERE IF(userID = $userID, 1, 0) = 0";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));

            $groups = new SplDoublyLinkedList();

            while($row = mysql_fetch_assoc($res))
            {
                $id = $row['id'];
                $name = $row['name'];                

                $groups->push(new Group($id, $name));
            }

            return $groups;
        }

        public static function getAllGroups()
        {
            $query = "SELECT * FROM groups";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));

            $groups = new SplDoublyLinkedList();

            while($row = mysql_fetch_assoc($res))
            {
                $id = $row['id'];
                $name = $row['name'];                

                $groups->push(new Group($id, $name));
            }

            return $groups;
        }

        public static function getAllUsersFromGroupID($groupID)
        {
            $query = "SELECT users.*
                      FROM group_user_relation
                      JOIN users
                      ON group_user_relation.userID = users.id
                      WHERE groupID = $groupID";           
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));

            $users = new SplDoublyLinkedList();

            while($row = mysql_fetch_assoc($res))
            {
                $id = $row['id'];
                $username = $row['username'];
                $email = $row['email'];
                $rankName = $row['rankName'];
                $lastLogin = $row['lastLogin'];
                $lastIP = $row['lastIP'];
                $registerDate = $row['registerDate'];


                $users->push(new User($id, $username, $email, $rankName, $lastLogin, $lastIP, $registerDate));        
            }

            return $users;
        }

        
    }
    
?>