<?php
    /**
     * 
     */
    class Ausloeser 
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

        public static function getAusloeserByID($id) 
        {
            $query = "SELECT * FROM ausloeser WHERE id = '$id'";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
            $row = mysql_fetch_assoc($res);

            return new Ausloeser($row['id'], $row['name']);            
        }

        public static function getAllAusloeser()
        {
            $query = "SELECT * FROM ausloeser";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));
            
            $ausloeser = new SplDoublyLinkedList();
            while($row = mysql_fetch_assoc($res))
            {
                $id = $row['id'];
                $name = $row['name'];

                $ausloeser->push(new Ausloeser($id, $name));
            }

            return $ausloeser;
        }
    }
    
?>