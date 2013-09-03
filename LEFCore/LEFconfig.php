<?php

    class LEFconfig
    {
        private static $__instance;
        private $lefconfigs;
        
        private function __construct() 
        {
            $lefconfigs_array = array();

            $lefconfigs_dir_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFconfig';
            $lefconfigs_dir = dir($lefconfigs_dir_path);

            while($lefconfigs_file = $lefconfigs_dir->read())
            {
                if(is_file($lefconfigs_dir_path.DIRECTORY_SEPARATOR.$lefconfigs_file) && ($lefconfigs_file!=basename(__FILE__)))
                {
                    $lefconfigs_array[str_replace('.conf.php', '', $lefconfigs_file)] = include_once($lefconfigs_dir_path.DIRECTORY_SEPARATOR.$lefconfigs_file);
                }
            }
            
            $lefconfigs = array();
            
            Utilities::normalizeArray($lefconfigs_array, $lefconfigs, false);
            
            $this -> lefconfigs = $lefconfigs;
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
        
        public function get($index)
        {
            if(isset($this->lefconfigs[$index]))
            {
                return $this->lefconfigs[$index];
            }
            return false;
        }
        
        public function getGroup($index)
        {
            $group = array();
            
            foreach($this->lefconfigs as $key => $value)
            {
                if(strpos($key, $index.'.')===0)
                {
                    $group[$key] = $value;
                }
            }
            
            return $group;
        }
        
        public function set($index, $value)
        {
            $this->lefconfigs[$index] = $value;
        }
    }