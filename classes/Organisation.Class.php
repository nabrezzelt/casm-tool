<?php
    /**
    * 
    */
    class Organisation
    {
        private $id;
        private $name;
        private $assignmentGroups;

        function __construct($id, $name, $assignmentGroups = null)
        {
            $this->id = $id;
            $this->name = $name;

            if($assignmentGroups == null)
            {
                $this->assignmentGroups = new SplDoublyLinkedList();
            } 
            else
            {
                $this->assignmentGroups = $assignmentGroups;
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

       public function setAssignmentGroups($assignmentGroups)
        {
            $this->assignmentGroups = $assignmentGroups;
        }

        public function getAssignmentGroups()
        {
            return $this->assignmentGroups;
        }       

        public static function getAllOrganisations()
        {
            $query = "SELECT * FROM organisation";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
            $organisations = new SplDoublyLinkedList();

            while($row = mysql_fetch_assoc($res))
            {
                $organisationID = $row['id'];
                $organisationName = $row['name'];
                $assignmentGroups = new SplDoublyLinkedList();

                $query = "SELECT assignment_group.*, `status`.name AS statusName
                          FROM assignment_group 
                          JOIN `status`
                          ON `status`.id = assignment_group.statusID
                          WHERE organisationID = $organisationID";
                $res2 = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));

                while($row2 = mysql_fetch_assoc($res2))
                {
                    $assignmentGroupID = $row2['id'];
                    $assignmentGroupName = $row2['name'];
                    $assignmentGroupDescription = $row2['description'];
                    $assignmentGroupStatus = new Status($row2['statusID'], $row2['statusName']);

                    $query = "SELECT assignment_sub_group.*, `status`.name AS statusName
                              FROM  assignment_sub_group
                              JOIN `status`
                              ON `status`.id = assignment_sub_group.statusID
                              WHERE assignmentGroupID = $assignmentGroupID";
                    $res3 = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
                    
                    $assignmentSubGroups = new SplDoublyLinkedList();

                    while ($row3 = mysql_fetch_assoc($res3)) 
                    {
                        $assignmentSubGroupID = $row3['id'];
                        $assignmentSubGroupName = $row3['name'];
                        $assignmentSubGroupDescription = $row3['description'];
                        $assignmentSubGroupStatus = new Status($row3['statusID'], $row3['statusName']);

                        $assignmentSubGroups->push(new AssignmentSubGroup($assignmentSubGroupID, $assignmentSubGroupName, $assignmentSubGroupDescription, $assignmentSubGroupStatus));
                    }
                    
                    $assignmentGroups->push(new AssignmentGroup($assignmentGroupID, $assignmentGroupName, $assignmentGroupDescription, $assignmentGroupStatus, $assignmentSubGroups));

                }

                $organisations->push(new Organisation($organisationID, $organisationName, $assignmentGroups));
            }

            return $organisations;
        }
    }



?>