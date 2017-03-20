<?php
    /**
     * 
     */
    class Permission 
    {
        const ADMIN_VIEW_USERLIST = 1;
        const ADMIN_VIEW_USER_DETAILS = 2;
        const ADMIN_VIEW_USER_PERMISSIONS = 3;
        const ADMIN_CHANGE_USER_PERMISSIONS = 4;
        const ADMIN_VIEW_USER_GROUPS = 5;
        const ADMIN_ADD_USER_TO_GROUP = 6;        
        const ADMIN_REMOVE_USER_FROM_GROUP = 7;
        const ADMIN_VIEW_GROUPLIST = 8;
        const ADMIN_VIEW_GROUP_DETAILS = 9;
        const ADMIN_VIEW_GROUP_MEMBERS = 10;
        const ADMIN_VIEW_GROUP_PERMISSIONS = 11;
        const ADMIN_CHANGE_GROUP_PERMISSIONS = 12;        
        const ADMIN_DELETE_GROUP = 13;
        const ADMIN_CREATE_GROUP = 14;

        const TOOL_VIEW_ASSIGNMENT_GROUPS = 15;
        const TOOL_CREATE_ASSIGNMENT_GROUP = 16;
        const TOOL_CREATE_ASSIGNMENT_SUB_GROUP = 17;
        const TOOL_VIEW_MENU_POINTS = 18;
        const TOOL_VIEW_TEMPLATES = 19;
        const TOOL_CHANGE_MENU_STATE = 20;
        const TOOL_DELETE_MENU_POINT = 21;
        const TOOL_CREATE_TEMPLATES = 22;
        const TOOL_CHANGE_TEMPLATES = 23;
        const TOOL_CREATE_MENU_POINT = 24;
        
        protected $id;
        protected $name;
        protected $errorCode;
        protected $errorMessage;
        
        function __construct($id, $name, $errorCode, $errorMessage)
        {
            $this->id = $id;
            $this->name = $name;
            $this->errorCode = $errorCode;
            $this->errorMessage = $errorMessage;            
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

        public function setErrorCode($errorCode)
        {
            $this->errorCode = $errorCode;
        }

        public function getErrorCode()
        {
            return $this->errorCode;
        }

        public function setErrorMessage($errorMessage)
        {
            $this->errorMessage = $errorMessage;
        }

        public function getErrorMessage()
        {
            return $this->errorMessage;
        }

        /**
        * undocumented function summary
        *
        * Undocumented function long description
        *
        * @param type var Description
        **/    
        public static function hasPermission($permissionID, $userID) 
        {
            $query =   "SELECT permissionID
                        FROM 
                        (
                            SELECT group_permissions.permissionID
                            FROM group_user_relation
                            JOIN group_permissions
                            ON group_user_relation.groupID = group_permissions.groupID
                            WHERE (userID = $userID AND permissionID = $permissionID)
                        ) AS grp_permissions
                        UNION
                            SELECT permissionID
                            FROM 
                            (
                                SELECT permissionID
                                FROM user_permissions
                                WHERE (userID = $userID AND permissionID = $permissionID)
                        ) AS acc_permissions";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));

            if (mysql_num_rows($res) == 1) 
            {
                return true;
            }
            return false;
        }

        /**
         * undocumented function summary
         *
         * Undocumented function long description
         *
         * @param type var Description
         **/
        public static function getPermissionByID($id)
        {
            $query = "SELECT * FROM permissions WHERE id = '$id'";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
            $row = mysql_fetch_assoc($res);

            return new Permission((int) $row['id'], $row['name'],(int) $row['errorCode'], $row['errorMessage']);
        }

        public static function getAllPermissionsByGroup($groupID)
        {
            $query = "SELECT groupID, permissions.*
                      FROM group_permissions
                      JOIN permissions 
                      ON group_permissions.permissionID = permissions.ID
                      WHERE groupID = '$groupID'";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));

            $permissions = new SplDoublyLinkedList();

            while($row = mysql_fetch_assoc($res))
            {
                $id = $row['id'];                
                $name = $row['name'];
                $errorCode = $row['errorCode'];
                $errorMessage = $row['errorMessage'];

                $permissions->push(new Permission($id, $name, $errorCode, $errorMessage));
            }

            return $permissions;
        }

        public static function saveUserPermissions($userID, $permissions)
        {
            Permission::removeAllPermissionsFromUser($userID);

            foreach ($permissions as $i => $value) 
            {
                $id = $permissions[$i];

                $query = "INSERT INTO user_permissions (userID, permissionID) VALUES ($userID, $id)";
                $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
            }           
        }

        public static function saveGroupPermissions($groupID, $permissions)
        {
            Permission::removeAllPermissionsFromGroup($groupID);

            foreach ($permissions as $i => $value) 
            {
                $id = $permissions[$i];

                $query = "INSERT INTO group_permissions (groupID, permissionID) VALUES ($groupID, $id)";
                $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
            } 
        }
        
        public static function removeAllPermissionsFromUser($userID)
        {
            $query = "DELETE FROM user_permissions WHERE userID = $userID";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }

        public static function removeAllPermissionsFromGroup($groupID)
        {
            $query = "DELETE FROM group_permissions WHERE groupID = $groupID";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }
    }
    
?>