<?php
    class LEFmain
    {
        private static $__instance;
        
        private function __construct() 
        {
            spl_autoload_register(array($this, 'LEFautoload'));
        }
        
        private function LEFautoload($class)
        {
            $folders = array
                (
                    'LEFconfig',
                    'LEFCore',
                    'LEFhooks',
                    'LEFlibs',
                    'LEFmodel',
                    'LEFthird',
                    'LEFview',
                );
            
            $class_path = str_replace('_', DIRECTORY_SEPARATOR, $class);
            
            foreach($folders as $index => $folder)
            {
                if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$class_path.'.php'))
                {
                    require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$class_path.'.php';
                }
            }
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
        
        public function run()
        {
            LEFlog::getInstance()->init();
            
            if(LEFconfig::getInstance()->get('databases.orm'))
            {
                LEForm::getInstance()->start();
            }
            if(LEFconfig::getInstance()->get('session.active'))
            {    
                LEFSession::getInstance()->start();
            }
            
            $request = new LEFRequest();
            
            if(LEFconfig::getInstance()->get('cache.active'))
            {
                
                if(LEFconfig::getInstance()->get('cache.session_cache_expire'))
                {
                    session_cache_expire(LEFconfig::getInstance()->get('cache.session_cache_expire'));
                }
                
                $serializerequest = $request->serialize();
                $serializesession = LEFSession::getInstance()->serialize();
                
                $cachefilename = md5($serializerequest.$serializesession);
                
                if(LEFcache::getInstance()->exists($cachefilename))
                {
                    if(LEFcache::getInstance()->isActive($cachefilename))
                    {
                        echo LEFcache::getInstance()->getCache($cachefilename);
                    }
                    else
                    {
                        LEFcache::getInstance()->delete($cachefilename);
                        $response = new LEFresponse($request);
                        $responsestr = $response->getResponse();
                        echo $responsestr;
                        LEFcache::getInstance()->create($responsestr);
                    }
                }
                else 
                {
                        $response = new LEFresponse($request);
                        $responsestr = $response->getResponse();
                        echo $responsestr;
                        LEFcache::getInstance()->create($cachefilename, $responsestr);
                }
            }
            else
            {
                $response = new LEFresponse($request);
                $responsestr = $response->getResponse();
                echo $responsestr;
            }
        }
    }
    
    function use_helper($helpername, $filename = __FILE__)
    {
        $helper = str_replace('.', DIRECTORY_SEPARATOR, $helpername).'Helper.php';
        if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFhelpers'.DIRECTORY_SEPARATOR.$helper))
        {
            include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFhelpers'.DIRECTORY_SEPARATOR.$helper);
        }
        else
        {
            LEFlog::getInstance()->log_error(E_WARNING, 'Helper: '.$helpername.' not found in '.dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFhelpers'.DIRECTORY_SEPARATOR.$helper, __FILE__, 107);
        }
    }