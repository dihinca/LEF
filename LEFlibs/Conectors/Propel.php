<?php

class Conectors_Propel 
{
    public function __construct()
    {
        
    }
    
    public function includes()
    {
        include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFthird'.DIRECTORY_SEPARATOR.'Log'.DIRECTORY_SEPARATOR.'Log.php');
        include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFthird'.DIRECTORY_SEPARATOR.'propel'.DIRECTORY_SEPARATOR.'runtime'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Propel.php');
        
        $port = '';
        if(LEFconfig::getInstance()->get('databases.orm.port'))
        {
            $port = ';port='.LEFconfig::getInstance()->get('databases.orm.port');
        }
        
        $dsn = LEFconfig::getInstance()->get('databases.orm.adapter').':host='.LEFconfig::getInstance()->get('databases.orm.host').$port.';dbname='.LEFconfig::getInstance()->get('databases.orm.dbname');
        
        $conf = array (
            'datasources' => 
            array (
              'lef' => 
              array (
                'adapter' => LEFconfig::getInstance()->get('databases.orm.adapter'),
                'connection' => 
                array (
                  'dsn' => $dsn,
                  'user' => LEFconfig::getInstance()->get('databases.orm.user'),
                  'password' => LEFconfig::getInstance()->get('databases.orm.password'),
                ),
              ),
              'default' => 'lef',
            ),
            'log' => 
            array (
              'type' => 'file',
              'name' => dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR.'propel.log',
              'ident' => 'propel',
              'level' => '7',
            ),
            'generator_version' => '1.6.7',
          );
        
        spl_autoload_register
        (
                function($class)
                {
                    if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFmodel'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'lef'.DIRECTORY_SEPARATOR.$class.'.php'))
                    {     
                        include_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFmodel'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'lef'.DIRECTORY_SEPARATOR.$class.'.php');                         
                    } 
                    else if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFmodel'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'lef'.DIRECTORY_SEPARATOR.'map'.DIRECTORY_SEPARATOR.$class.'.php'))
                    {     
                        include_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFmodel'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'lef'.DIRECTORY_SEPARATOR.'map'.DIRECTORY_SEPARATOR.$class.'.php');                         
                    } 
                    else if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFmodel'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'lef'.DIRECTORY_SEPARATOR.'om'.DIRECTORY_SEPARATOR.$class.'.php'))
                    {     
                        include_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFmodel'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'lef'.DIRECTORY_SEPARATOR.'om'.DIRECTORY_SEPARATOR.$class.'.php');                         
                    } 
                }
        );
        
        Propel::setConfiguration($conf);
    }
}

