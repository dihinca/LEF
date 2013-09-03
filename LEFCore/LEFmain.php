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
                    'LEFmodules',
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
            if(LEFconfig::getInstance()->get('session.active'))
            {    
                LEFSession::getInstance()->start();
            }
            
            $request = new LEFRequest();
            $existcache = false;
            $loadcache = false;
            
            if(LEFconfig::getInstance()->get('cache.active'))
            {
                $loadcache = true;
                
                $cachecontent = '';
                
                $serializerequest = $request->serialize();
                $serializerouting = $request->getRouting()->serialize();
                
                if(LEFconfig::getInstance()->get('cache.session_cache_expire'))
                {
                    session_cache_expire(LEFconfig::getInstance()->get('cache.session_cache_expire'));
                }
                
                $sce = session_cache_expire();
                
                $cachefilename = md5($serializerequest.$serializerouting);
                
                $currenttime = time();
                
                if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$cachefilename.'.cache'))
                {
                    $expiretime = filemtime(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$cachefilename.'.cache')+($sce*60);
                    if($expiretime<=$currenttime)
                    {
                        unlink(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$cachefilename.'.cache');
                    }
                    else
                    {
                        $file = fopen(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$cachefilename.'.cache', 'r');
                        
                        while(!feof($file))
                        {
                            $cachecontent .= fgets($file).PHP_EOL;
                        }
                        
                        fclose($file);
                        
                        $existcache = true;
                    }
                }
            }
            
            if($loadcache && $existcache)
            {
                echo $cachecontent;
            }
            else if(!$loadcache || ($loadcache && !$existcache))
            {
                $modulename = $request->getModule();
                $modulename .= 'Module';
                $actionname = $request->getAction();
                $actionname = 'action'.ucfirst($actionname);
                $actionclass = new $modulename();
                $actionclass -> init($request);
                $actionclass -> $actionname();
                $paramstoview = $actionclass -> getParams();
                
                $layout = $actionclass->getLayout();
                $template = $actionclass->getTemplate();
                
                $view = new LEFview($paramstoview, $layout, $template);
                $view->makeRender();
                $strview = $view->getString();
                
                if($loadcache && !$existcache)
                {
                    ob_start();
                }
                
                echo $strview;
            }
            
            if($loadcache && !$existcache)
            {
                $cached = fopen(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$cachefilename.'.cache', 'w');
                fwrite($cached, ob_get_contents());
                fclose($cached);
                ob_end_flush();
            }
        }
    }