<?php
    /**
     * 
     */
    class ExtendedPermission extends Permission
    {
        public $hasPerm;

        function __construct($id, $name, $errorCode, $errorMessage, $hasPermission)
        {
            $this->hasPermission = (bool) $hasPermission;

            parent::__construct($id, $name, $errorCode, $errorMessage);
        }

        public function hasPerm()
        {
            return $this->hasPermission;
        }

        public static function getAllUserPermission($userID) 
        {
            $query = "  SELECT permissionID, hasPermission, name, errorCode, errorMessage
                        FROM (
                                SELECT permissionID, SUM(hasPermission) AS hasPermission
                                FROM (
                                        SELECT id AS permissionID, 0 AS hasPermission
                                        FROM permissions UNION ALL
                                        SELECT permissionID, 1 AS hasPermission
                                        FROM user_permissions
                                        WHERE ( userID = $userID )) AS inner_table
                                GROUP BY permissionID) A1
                                LEFT JOIN (
                                            SELECT *
                                            FROM permissions) AS A2
                                ON A1.permissionID = A2.id";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));

            $permissions = new SplDoublyLinkedList();

            while ($row = mysql_fetch_assoc($res)) 
            {
                $permissionID = $row['permissionID'];
                $hasPermission = $row['hasPermission'];
                $name = $row['name'];
                $errorCode = $row['errorCode'];
                $errorMessage = $row['errorMessage'];
                
                $permissions->push(new ExtendedPermission($permissionID, $name, $errorCode, $errorMessage, $hasPermission));
            }

            return $permissions;
        }

        public static function getAllGroupPermission($groupID)
        {
            $query = "  SELECT permissionID, hasPermission, name, errorCode, errorMessage
                        FROM (
                                SELECT permissionID, SUM( hasPermission ) AS hasPermission
                                FROM (
                                        SELECT id AS permissionID, 0 AS hasPermission
                                        FROM permissions
                                        UNION ALL
                                        SELECT permissionID, 1 AS hasPermission
                                        FROM group_permissions
                                        WHERE ( groupID = $groupID )		
                                ) AS inner_table
                                GROUP BY permissionID
                                ) A1
                                LEFT JOIN (		
                                            SELECT *
                                            FROM permissions
                                            ) AS A2 ON A1.permissionID = A2.id";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));

            $permissions = new SplDoublyLinkedList();

            while ($row = mysql_fetch_assoc($res)) 
            {
                $permissionID = $row['permissionID'];
                $hasPermission = $row['hasPermission'];
                $name = $row['name'];
                $errorCode = $row['errorCode'];
                $errorMessage = $row['errorMessage'];
                
                $permissions->push(new ExtendedPermission($permissionID, $name, $errorCode, $errorMessage, $hasPermission));
            }

            return $permissions;
        }
    }
    

?>