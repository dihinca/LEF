<?php

    class LEFRouting 
    {
        protected $rules;
        protected $request;
        private static $__instance;
        
        public function __construct($getrequest = '')
        {
            $this -> rules = LEFconfig::getInstance()->getGroup('routing.rules');
            $this -> request = $getrequest;
        }
        
        public function translate()
        {
            if(trim($this->request)=='')
            {
                return array('module' => LEFconfig::getInstance()->get('routing.defaultmodule'), 'action' => LEFconfig::getInstance()->get('routing.defaultaction'));
            }
            if(count($this->rules)==0)
            {
                $getrequest = explode('/', $getrequest);
                
                $arrayget = array();
                
                foreach($getrequest as $key => $value)
                {
                    if($key==0)
                    {
                        $arrayget['module'] = $value;
                    }
                    elseif($key==1)
                    {
                        $arrayget['action'] = $value;
                    }
                    else if(($key%2)==0)
                    {
                        $paramname = $value;
                        $_arrayget[$paramname] = '';
                    }
                    else
                    {
                        $_arrayget['paramname'] = $value;
                    }
                }
                
                return $arrayget;
            }
            else
            {
                $arrayget = array();
                $founded = false;
                
                foreach($this->rules as $key => $value)
                {
                    $rule = json_decode($value, true);
                    if((preg_match($rule['pattern'], $this->request)==1) && (!$founded))
                    {
                        $founded = true;
                        foreach($rule['vars'] as $keyvar => $valuevar)
                        {
                            $arrayget[$keyvar] = $valuevar;
                        }
                        
                        $request = explode('/', $this->request);
                        
                        $partrule = explode('/', $rule['rule']);
                        
                        $counter = 0;
                        
                        foreach($request as $keyvar => $valuevar)
                        {
                            if(isset($partrule[$keyvar]))
                            {
                                if(strpos(':', $partrule[$keyvar])!==false)
                                {
                                    $_GET[str_replace(':', '', $partrule[$keyvar])] = $valuevar;
                                }
                            }
                            else
                            {
                                if(($counter%2)==0)
                                {
                                    $paramname = $valuevar;
                                    $_GET[$paramname] = '';
                                }
                                else
                                {
                                    $_GET[$paramname] = $valuevar;
                                }
                                $counter++;
                            }
                        }
                    }
                }
                
                if(!$founded)
                {
                    $getrequest = explode('/', $getrequest);

                    foreach($getrequest as $key => $value)
                    {
                        if($key==0)
                        {
                            $arrayget['module'] = $value;
                        }
                        elseif($key==1)
                        {
                            $arrayget['action'] = $value;
                        }
                        else if(($key%2)==0)
                        {
                            $paramname = $value;
                            $arrayget[$paramname] = '';
                        }
                        else
                        {
                            $arrayget['paramname'] = $value;
                        }
                    }
                }
                
                if(!isset($arrayget['module']))
                {
                    $arrayget['module'] = LEFconfig::getInstance()->get('routing.defaultmodule');
                }
                
                if(!isset($arrayget['action']))
                {
                    $arrayget['action'] = LEFconfig::getInstance()->get('routing.defaultaction');
                }
                
                return $arrayget;
            }
        }
    }
