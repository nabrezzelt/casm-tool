<?php
    class SQLException extends Exception
    {    
        private $query;

        public function __construct($message, $query, $code = 0, Exception $previous = null) 
        {
            $this->query = $query;

            parent::__construct($message, $code, $previous);
        }
        
        public function __toString() 
        {
            return __CLASS__ . ": [{$this->code}]: {$this->message}\n Query: {$this->query}";
        }     

        public function getQuery() 
        {
            return $this->query;
        }  
    }
?>