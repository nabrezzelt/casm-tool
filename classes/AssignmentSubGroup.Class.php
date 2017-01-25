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
    }
    
?>