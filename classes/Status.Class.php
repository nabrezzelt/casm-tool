<?php
    /**
     * 
     */
    class Status
    {
        
        private $id;
        private $name;            

        function __construct($id, $name)
        {
            $this->id = (int) $id;
            $this->name = $name;
        }

        public function getID() 
        {
            return $this->id;
        }

        public function getName()
        {
            return $this->name;
        }

        public static function getStatusByID($id) 
        {
            $query = "SELECT * FROM status WHERE id = '$id'";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
            $row = mysql_fetch_assoc($res);

            return new Status($row['id'], $row['name']);            
        }

        public static function getAllStatus()
        {
            $query = "SELECT * FROM status";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
            
            $status = new SplDoublyLinkedList();
            while($row = mysql_fetch_assoc($res))
            {
                $id = $row['id'];
                $name = $row['name'];

                $status->push(new Status($id, $name));
            }

            return $status;
        }
    }
    
?>