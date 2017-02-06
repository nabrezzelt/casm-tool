<?php
    /**
     * 
     */
    class AssignmentSubGroup
    {
        private $id;
        private $name;
        private $description;
        private $status;

        function __construct($id, $name, $description, $status)
        {
            $this->id = $id;
            $this->name = $name;
            $this->description = $description;
            $this->status = $status;                                
        }

        public function setID($id)
        {
            $this->id = $id;
        }

        public function getID()
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

        public function setStatus($status)
        {
            $this->status = $status;
        }

        public function getStatus()
        {
            return $this->status;
        }

        public static function create($assignmentGroupID, $name, $description, $statusID)
        {
            $query = "INSERT INTO assignment_sub_group (assignmentGroupID, name, description, statusID) VALUES ($assignmentGroupID, '$name', '$description', $statusID)";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }

        public static function saveDescription($id, $description)
        {
            $query = "UPDATE assignment_sub_group SET description = '$description' WHERE id = $id";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }

        public static function getGroupByID($id)
        {
            $query = "SELECT assignment_sub_group.*, `assignment_status`.name AS statusName
                      FROM  assignment_sub_group
                      JOIN `assignment_status`
                      ON `assignment_status`.id = assignment_sub_group.statusID
                      WHERE assignmentGroupID = $id";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));                   
            $row = mysql_fetch_assoc($res); 
                    
            $id = $row['id'];
            $name = $row['name'];
            $description = $row['description'];
            $status = new AssignmentStatus($row['statusID'], $row['name']);

            return new AssignmentSubGroup($id, $name, $description, $status);            
        }
    }
    
?>