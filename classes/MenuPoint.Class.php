<?php
    /**
     * 
     */
    class MenuPoint
    {
        private $id;
        private $name;
        private $description;
        private $subPoints;

        function __construct($id, $name, $description, $subPoints)
        {
            $this->id = $id;
            $this->name = $name;
            $this->description = $description;
            
            if($subPoints == null)
            {
                $this->subPoints == new SplDoublyLinkedList();
            }
            else
            {
                $this->subPoints = $subPoints; 
            }
        }

        public function setID($id)
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

        public function setDescription($description)
        {
            $this->description = $description;
        }

        public function getDescription()
        {
            return $this->description;
        }

        public function setSubPoints($subPoints)
        {
            $this->subPoints = $subPoints;
        }

        public function getSubPoints()
        {
            return $this->subPoints;
        }

        public static function printChildsAsList($parentid, $colspan) 
        {
            $re = "";
            if($parentid == null)
            {
                $query = "SELECT * FROM menu_point WHERE parentID IS NULL";
            }
            else
            {
                $query = "SELECT * FROM menu_point WHERE parentID = '" . $parentid . "'";
            }

            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));

            while($row = mysql_fetch_assoc($res)):
                $i = 0;
                
                $margin = $colspan+25;
                $re .=  "<tr>
                            <td style='padding-left:" . $margin . "px;'>" . $row['name'] . "</td>";

                            $query = "  SELECT roles.id as roleID, roles.name, A.menuID, IF(A.id IS NULL, 0, 1) AS hasAccess
                                        FROM roles
                                        LEFT JOIN (
                                                    SELECT roles.id, menu_point_access.menuID
                                                    FROM menu_point_access
                                                    LEFT OUTER JOIN roles
                                                    ON roles.id = menu_point_access.roleID
                                                    where menuid = " . $row['id'] . ") AS A
                                        ON A.id = roles.id";
                            $res2 = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));

                            while($row2 = mysql_fetch_assoc($res2))
                            {
                                $re .= "<td>";
                                if($row2['hasAccess'] == 1)
                                {
                                    $re .= "<span class='remove-menu-point-permission' data-role='" . $row2['roleID'] . "' data-menu='" . $row['id'] . "' ><span class='glyphicon glyphicon-ok allowed'></span></span>"; //href='menu-points.php?act=remove-menu-point&menu-point=" . $row['id'] . "&role=" . $row2['roleID'] . "'
                                }
                                else
                                {
                                    $re .= "<span class='add-menu-point-permission' data-role='" . $row2['roleID'] . "' data-menu='" . $row['id'] . "'><span class='glyphicon glyphicon-remove not-allowed'></span></span>"; // href='menu-points.php?act=add-menu-point&menu-point=" . $row['id'] . "&role=" . $row2['roleID'] . "'
                                }
                                $re .= "</td>";
                            }                                      
                            

                    $re .= "<td>
                                <a class='btn btn-default btn-xs' href='menu-points.php?act=menu-point-delete&menu-point=" . $row['id'] . "'>Delete</a> 
                            </td>
                        <tr>";
                $re .= MenuPoint::printChildsAsList($row['id'], $margin);                
                $i++;

              
            endwhile;

            return $re;            
        }

        public static function addRolePermission($role, $menu)
        {
            $query = "INSERT INTO menu_point_access (roleID, menuID) VALUES ($role, $menu)";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }

        public static function remove($role, $menuPoint)
        {
            $query = "DELETE FROM menu_point_access WHERE roleID = $role AND menuID = $menuPoint";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }

        public static function delete($menuPointID)
        {          
            MenuPoint::deleteAllPermissions($menuPointID);
              
            $query = "DELETE FROM menu_point WHERE id = $menuPointID";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }

        public static function deleteAllPermissions($menuPointID)
        {
            $query = "DELETE FROM menu_point_access WHERE menuID = $menuPointID";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }

        public static function create($name, $parentID, $description)
        {
            if(((int) $parentID) == 0)
            {
                $query = "INSERT INTO menu_point (name, parentID, description) VALUES (\"$name\", NULL, \"$description\")";
            }
            else 
            {
                $query = "INSERT INTO menu_point (name, parentID, description) VALUES (\"$name\", $parentID, \"$description\")";
            }

            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }
    }
    
?>