<?php

    class LEFSession 
    {
        private static $__instance;
        protected $authenticated;
        
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
            $groupconfigs = LEFconfig::getInstance()->getGroup('session');
            
            foreach($groupconfigs as $key => $value)
            {
                ini_set(str_replace('session.', '', $key), $value);
            }
            
            session_start();
        }
        
        public function auth()
        {
            $this -> authenticated = true;
            $_SESSION['authenticate'] = true;
        }
        
        public function deauth()
        {
            $this -> authenticated = true;
            unset($_SESSION['authenticate']);
        }
        
        public function get($varname)
        {
            if(isset($_SESSION[$varname]))
            {
                return $_SESSION[$varname];
            }
            return false;
        }
        
        public function set($varname, $varvalue)
        {
            $_SESSION[$varname] = $varvalue;
        }
        
        public function unseting($varname)
        {
            unset($_SESSION[$varname]);
        }
        
        public function serialize()
        {
            $serial = '';
            
            if(isset($_SESSION))
            {
                foreach($_SESSION as $key => $value)
                {
                    $serial .= $key.':'.$value;
                }
            }
            
            return $serial;
        }
    }

?>
