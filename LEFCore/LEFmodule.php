<?php

class LEFmodule 
{
    protected $request;
    protected $params = array();
    protected $layout = false;
    protected $module;
    protected $action;
    protected $template;


    public function init($request)
    {
        $this->layout = LEFconfig::getInstance()->get('view.layout');
        $this -> request = $request;
        $this->module = $request->getModule();
        $this -> action = $request->getAction();
        $this ->template = $request->getModule().ucfirst($request->getAction());
    }
    
    public function getParams()
    {
        return $this -> params;
    }
    
    public function __set($name, $value)
    {
        $this->params[$name] = $value;
    }
    
    public function getAction()
    {
        $this -> action;
    }
    
    public function getModule()
    {
        $this -> module;
    }
    
    public function setLayout($layoutname)
    {
        $this -> layout  = $layoutname;
    }
    
    public function getLayout()
    {
        return $this->layout;
    }
    
    public function setTemplate($templatename)
    {
        $this->template = $templatename;
    }
    
    public function getTemplate()
    {
        return $this->template;
    }
}

?>
