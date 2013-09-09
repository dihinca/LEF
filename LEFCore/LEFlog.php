<?php

    class LEFlog
    {
        private static $__instance;
        
        private function __construct() 
        {
            $this -> traceuniqueidcode = uniqid();
            $this -> track = array();
            $this -> datetime = date('Y-m-d H:i:s, Time Zone='. date_default_timezone_get());
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
        
        public function init()
        {
            error_reporting( E_ALL );
            
            set_error_handler(array($this, 'log_error'), E_ALL);
            set_exception_handler(array($this, 'log_exception'));
            register_shutdown_function(array($this, 'shutdown_log'));
        }
        
        public function log_error($errno, $errstr, $errfile, $errline)
        {
            $this -> addTrack(self::error_level_tostring($errno, ' ').' '.$errstr.', file: '.$errfile.', line: '.$errline.PHP_EOL);
            if(in_array($errno, array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR)))
            {       
                $message = 'We have a problem, the code is: '.$this->getTracecode();
                $this-> saveLog();
                echo $message;
                die();
            }
        }
    
        public function getTrack()
        {
            $stringtrack = 'Log track for execution code: '.$this->getTracecode().PHP_EOL;
            $stringtrack .= 'this execution start in '.$this->datetime.PHP_EOL.PHP_EOL.PHP_EOL;
            foreach($this->track as $k => $v)
            {
                $stringtrack .= $k.'=>'.$v.PHP_EOL;
            }
            return $stringtrack;
        }
    
        public function log_exception(Exception $e)
        {
            $this -> addTrack('Exception dont catch: '.$e->getCode().', '.$e->getMessage().', file: '.$e->getFile().', line: '.$e->getLine().PHP_EOL.', Trace:'.PHP_EOL);

            $trace = $e->getTrace();
            foreach($trace as $k => $v)
            {
                $this -> addTrack($k.'=>'.$v.PHP_EOL);
            }
            echo 'We have a problem, the code is: '.$this->getTracecode();
        }

        public function shutdown_log($message = false)
        {
            $error = error_get_last();
            if(in_array($error['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR)))
            {       
                $this->log_error($error['type'], $error['message'], $error['file'], $error['line']);
                $message = 'We have a problem, the code is: '.$this->getTracecode();
                $this-> saveLog();
                echo $message;
                die();
            }
            else
            {
                $this-> saveLog();
            }
        }
    
        public function addTrack($message = '')
        {
            $this -> track[] = $message;
        }
    
        private static function error_level_tostring($intval, $separator)
        {
            $errorlevels = array(
                2047 => 'E_ALL',
                1024 => 'E_USER_NOTICE',
                512 => 'E_USER_WARNING',
                256 => 'E_USER_ERROR',
                128 => 'E_COMPILE_WARNING',
                64 => 'E_COMPILE_ERROR',
                32 => 'E_CORE_WARNING',
                16 => 'E_CORE_ERROR',
                8 => 'E_NOTICE',
                4 => 'E_PARSE',
                2 => 'E_WARNING',
                1 => 'E_ERROR');
            $result = '';
            foreach($errorlevels as $number => $name)
            {
                if (($intval & $number) == $number) {
                    $result .= ($result != '' ? $separator : '').$name; }
            }
            return $result;
        }
    
        public function getTracecode()
        {
            return $this->traceuniqueidcode;
        }

        public function saveLog()
        {
            $path = dirname(realpath(__FILE__)).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR.'log'.date('Ymd').'.log';
            $fp = fopen($path, 'a+');

            fwrite($fp, PHP_EOL.PHP_EOL.PHP_EOL.$this->getTrack());

            fclose($fp);
        }
    }
