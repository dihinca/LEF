<?php

class LEFresponse 
{
    private $request;
    
    public function __construct($request)
    {
        $this -> request = $request;
    }
    
    public function getResponse()
    {
        $modulename = $this->request->getModule();
        if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFmodules'.DIRECTORY_SEPARATOR.$modulename.DIRECTORY_SEPARATOR.$modulename.'Module.php'))
        {
            include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFmodules'.DIRECTORY_SEPARATOR.$modulename.DIRECTORY_SEPARATOR.$modulename.'Module.php');
            
            $modulename .= 'Module';
            $actionname = $this->request->getAction();
            $actionname = 'action'.ucfirst($actionname);
            $actionclass = new $modulename();
            $actionclass -> init($this->request);
            $actionclass -> $actionname();
            $paramstoview = $actionclass -> getParams();
            $module = $actionclass->getModule();
            $action = $actionclass->getAction();

            $layout = $actionclass->getLayout();
            $template = $actionclass->getTemplate();

            $view = new LEFview($paramstoview, $layout, $template, $module, $action);
            $view->makeRender();
            $strview = $view->getString();
            return $strview;
        }
        return '';
    }
}
