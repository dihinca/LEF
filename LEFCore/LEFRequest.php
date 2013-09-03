<?php
    class LEFRequest 
    {
        protected $module;
        protected $action;
        protected $get;
        protected $post;
        private static $__instance;
        protected $routing;


        public function __construct() 
        {
            $getrequest = $_GET['getrequest'];
            unset($_GET['getrequest']);
            $routing = new LEFRouting($getrequest);
            $_GET = $routing->translate();
            
            $this -> module = $_GET['module'];
            $this -> action = $_GET['action'];
            
            unset($_GET['module']);
            unset($_GET['action']);
            
            $this -> get  = $_GET;
            $this -> post  = $_POST;
            $this -> routing = $routing;
        }
        
        public function getGetParam($paramname)
        {
            return $this->get[$paramname];
        }
        
        public function getPostParam($paramname)
        {
            return $this->post[$paramname];
        }
        
        public function getGetParams()
        {
            return $this->get;
        }
        
        public function getPostParams()
        {
            return $this->post;
        }
        
        public function serialize()
        {
            $serial = '';
            foreach($this -> get as $key => $value)
            {
                $serial .= $key.':'.$value;
            }
            
            foreach($this -> post as $key => $value)
            {
                $serial .= $key.':'.$value;
            }
            
            return $serial;
        }
        
        public function getRouting()
        {
            return $this->routing;
        }
        
        public function getAction()
        {
            return $this->action;
        }
        
        public function getModule()
        {
            return $this->module;
        }
    }
