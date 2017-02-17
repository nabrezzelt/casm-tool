<?php
    /**
     * 
     */
    class Service 
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

        public static function getAllServices()
        {
            $query = "SELECT * FROM services";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
            
            $services = new SplDoublyLinkedList();
            while($row = mysql_fetch_assoc($res))
            {
                $id = $row['id'];
                $name = $row['name'];

                $services->push(new Service($id, $name));
            }

            return $services;
        }      

    }
    
?>