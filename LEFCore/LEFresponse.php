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
        $modulename .= 'Module';
        $actionname = $this->request->getAction();
        $actionname = 'action'.ucfirst($actionname);
        $actionclass = new $modulename();
        $actionclass -> init($this->request);
        $actionclass -> $actionname();
        $paramstoview = $actionclass -> getParams();

        $layout = $actionclass->getLayout();
        $template = $actionclass->getTemplate();

        $view = new LEFview($paramstoview, $layout, $template);
        $view->makeRender();
        $strview = $view->getString();
        return $strview;
    }
}
