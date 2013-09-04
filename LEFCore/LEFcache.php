<?php

class LEFcache 
{
        private static $__instance;
        
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
        
        public function exists($cachefilename)
        {
            if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$cachefilename.'.cache'))
            {
                return true;
            }
            return false;
        }
        
        public function isActive($cachefilename)
        {
            $sce = session_cache_expire();
            $expiretime = filemtime(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$cachefilename.'.cache')+($sce*60);
            if($expiretime>time())
            {
                return true;
            }
            return false;
        }
        
        public function delete($cachefilename)
        {
            return unlink(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$cachefilename.'.cache');
        }
        
        public function getCache($cachefilename)
        {
            $cachecontent = '';
            
            $file = fopen(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$cachefilename.'.cache', 'r');

            while(!feof($file))
            {
                $cachecontent .= fgets($file).PHP_EOL;
            }

            fclose($file);
            
            return $cachecontent;
        }
        
        public function create($cachefilename, $content)
        {
            $cached = fopen(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$cachefilename.'.cache', 'w');
            fwrite($cached, $content);
            fclose($cached);
            return true;
        }
}

?>
