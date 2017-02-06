<?php
    /**
     * 
     */
    class AssignmentGroup
    {
        private $id;

        private $name;
        private $description;        
        private $status;
        private $assignmentSubGroups;

        function __construct($id, $name, $description, $status, $assignmentSubGroups = null)
        {
            $this->id = $id;
            $this->name = $name;
            $this->description = $description;
            $this->status = $status;         

            if($assignmentSubGroups == null)
            {
                $this->assignmentSubGroups = new SplDoublyLinkedList();
            } 
            else
            {
                $this->assignmentSubGroups = $assignmentSubGroups;
            }   
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

        public function setAssignmentSubGroups($assignmentSubGroups)
        {
            $this->assignmentSubGroups = $assignmentSubGroups;
        }

        public function getAssignmentSubGroups()
        {
            return $this->assignmentSubGroups;
        }

        public static function getAllAssignmentGroupsWithoutSubGroups()
        {
            $query = "SELECT assignment_group.*, `assignment_status`.name AS statusName
                          FROM assignment_group 
                          JOIN `assignment_status`
                          ON `assignment_status`.id = assignment_group.statusID";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
            $assignmentGroups = new SplDoublyLinkedList();

            while($row = mysql_fetch_assoc($res))
            {
                $id = $row['id'];
                $name = $row['name'];
                $description = $row['description'];
                $status = new AssignmentStatus($row['statusID'], $row['statusName']);

                $assignmentGroups->push(new AssignmentGroup($id, $name, $description, $status));
            }

            return $assignmentGroups;
        }

        public static function create($organisationID, $name, $description, $statusID)
        {
            $query = "INSERT INTO assignment_group (organisationID, name, description, statusID) VALUES ($organisationID, '$name', '$description', $statusID)";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }

        public static function getGroupByID($id)
        {
            $query = "SELECT assignment_group.*, `assignment_status`.name AS statusName
                          FROM assignment_group 
                          JOIN `assignment_status`
                          ON `assignment_status`.id = assignment_group.statusID
                          WHERE assignment_group.id = $id";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));            

            $row = mysql_fetch_assoc($res);
            
            $id = $row['id'];
            $name = $row['name'];
            $description = $row['description'];
            $status = new AssignmentStatus($row['statusID'], $row['statusName']);

            return new AssignmentGroup($id, $name, $description, $status);
        }

        public static function saveDescription($id, $description)
        {
            $query = "UPDATE assignment_group SET description = '$description' WHERE id = $id";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
        }
    }
    
?>