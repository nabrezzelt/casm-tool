<?php    
    require_once("exceptions/DatabaseException.php");
    /**
     * Database Wrapper Class
     */
    class Database extends PDO
    {
        /**
        * Simple check that the database connection has been made
        * @var bool $_connect
        */
        private $_connect;

        /**
        * PDO Connection String 
        * @var string $_dsn
        */
        private $_dsn = '';
        
        /**
        * PDO User 
        * @var string $_username
        */
        private $_username = '';

        /**
        * PDO Password 
        * @var string $_password
        */        
        private $_password = '';

        /**
        * PDO Driver 
        * @var mixed $_driver_options
        */
        private $_driver_options = array();        

        function __construct($dsn, $username, $password, $driver_options)
        {
            $this->_dsn = $dsn;
            $this->_username = $username;
            $this->_password = $password;
            $this->_driver_options = $driver_options;
        }

        public function __connect() {
            try
            {
                parent::__construct($this->_dsn, $this->_username, $this->_password, $this->_driver_options);
                $this->_connect = true;
                $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);                 
            }
            catch (PDOException $e) 
            {
                throw new DatabaseException("Can't connect do Database.");
            }
        }

        /**
        * @see parent::query()
        */
        public function query($statement) 
        {
            if (!$this->_connect)
                $this->__connect();            
            return parent::query($statement);
        }

        /**
        * @see parent::beginTransaction()
        */
        public function beginTransaction() 
        {
            if (!$this->_connect)
                $this->__connect();
            return parent::beginTransaction();
        }

        /**
        * @see parent::errorCode()
        */
        public function errorCode() 
        {
            if (!$this->_connect)
                $this->__connect();
            return parent::errorCode();
        }

        /**
        * @see parent::errorInfo()
        */
        public function errorInfo() 
        {
            if (!$this->_connect)
                $this->__connect();
            return parent::errorInfo();
        }

        /**
        * @see parent::exec()
        */
        public function exec($statement) 
        {
            if (!$this->_connect)
                $this->__connect();
            return parent::exec($statement);
        }
        /**
        * @see parent::prepare()
        */
        public function prepare($statement, $driver_options = array()) 
        {
            if (!$this->_connect)
                $this->__connect();
            return parent::prepare($statement, $driver_options);
        }
        
    }
    

?>