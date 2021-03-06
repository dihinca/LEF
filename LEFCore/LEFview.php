<?php

class LEFview 
{
    protected $params;
    protected $layout;
    protected $template;
    protected $render;
    protected $module;
    protected $action;

    public function getString() 
    {
        return $this->render;
    }
    
    public function __construct($params, $layout, $template, $module, $action)
    {
        $this -> params = $params;
        $this -> layout = $layout;
        $this -> template = $template;
        $this -> module = $module;
        $this -> action = $action;
        $this -> render = '';
    }
    
    public function makeRender()
    {   
        ob_start();
        
        foreach($this -> params as $key => $value)
        {
            $$key = $value;
        }
        
        include_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFview'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.$this->module.DIRECTORY_SEPARATOR.$this->template.'.php');
        
        $templatecontent = ob_get_contents();
        ob_clean();
        
        include_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFview'.DIRECTORY_SEPARATOR.'layouts'.DIRECTORY_SEPARATOR.$this->layout.'.php');
        
        $this->render = ob_get_contents();
        
        ob_end_clean();
    }
}
