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

    }
    
?>