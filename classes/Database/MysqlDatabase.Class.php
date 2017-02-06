<?php
    require_once("Database.Class.php");
    /**
     * MySQL Wrapper Class
     *
     * @link https://stackoverflow.com/questions/159924/how-do-i-loop-through-a-mysql-query-via-pdo-in-php
     */
    class MysqlDatabase extends Database
    {
        /**
        * Check if php can use the PDO-MySQL-Driver to connect to database
        *
        * @return bool true if it is valid to use this driver
        */
        public static function enabled()
        {
            return in_array('mysql', PDO::getAvailableDrivers());
        }

        function __construct($host, $username, $password, $database, $port = 3306, $utf8 = false, $driver_options = array())
        {
            $driver_options += [PDO::ATTR_PERSISTENT =>  true];
            $driver_options += [PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true];

            parent::__construct('mysql:dbname=' . $database . ';host=' . $host . ';port=' . $port . (($utf8) ? ';charset=utf8' : ''), $username, $password, $driver_options);
        }

        public function insertUpdateDelete($statement)
        {
            if($this->exec($statement))
            {
                return true;
            }            
            else
            {
                return false;
            }
        }
    }
    
?>