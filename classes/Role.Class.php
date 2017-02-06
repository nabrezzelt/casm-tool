<?php
    /**
     * 
     */
    class Role 
    {
        private $id;
        private $name;        

        function __construct($id, $name)
        {
            $this->id = $id;
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

        public static function getAllRoles()
        {
            $query = "SELECT * FROM roles ORDER BY id";
            $res = mysql_query($query)or die(Helper::SQLErrorFormat(mysql_error(), $query, __METHOD__, __FILE__, __LINE__));

            $roles = new SplDoublyLinkedList();

            while($row = mysql_fetch_assoc($res))
            {
                $id = $row['id'];
                $name = $row['name'];                

                $roles->push(new Role($id, $name));
            }

            return $roles;
        }
    }
    
?>