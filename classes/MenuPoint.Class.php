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

                            $query = "  SELECT *, IF(A.id IS NULL, 0, 1) AS hasAccess
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
                                    $re .= "<span class='glyphicon glyphicon-ok'></span>";
                                }
                                else
                                {
                                    $re .= "<span class='glyphicon glyphicon-remove'></span>";
                                }
                                $re .= "</td>";
                            }                                      
                            

                    $re .= "<td>
                                Menü/Delete
                            </td>
                        <tr>";
                $re .= MenuPoint::printChildsAsList($row['id'], $margin);                
                $i++;

              
            endwhile;

            return $re;            
        }
    }
    
?>