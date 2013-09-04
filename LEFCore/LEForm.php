<?php

class LEForm 
{
    
        private static $__instance;
        protected $conector;
        
        private function __construct() 
        {
        }
        
        public static function getInstance()
        {
            if(!isset(self::$__instance))
            {
                $class = __CLASS__;
                self::$__instance = new $class();
            }
            
            return self::$__instance;
        }
        
        public function start()
        {
            $class = 'Conectors_'.LEFconfig::getInstance()->get('databases.orm');
            $this -> conector = new $class();
            $this -> conector -> includes();
        }
}

?>
