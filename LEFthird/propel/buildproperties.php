<?php

    $configuracion = include(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFconfig'.DIRECTORY_SEPARATOR.'databases.conf.php');
    $fch = fopen(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'LEFconfig'.DIRECTORY_SEPARATOR.'propel'.DIRECTORY_SEPARATOR.'build.properties', 'a+');
        
    $port = '';
    if(isset($configuracion['orm.port']))
    {
        $port = ';port='.$configuracion['orm.port'];
    }

    $dsn = $configuracion['orm.adapter'].':host='.$configuracion['orm.host'].$port.';dbname='.$configuracion['orm.dbname'];
        
    fwrite($fch, 'propel.project = lef'.PHP_EOL);
    fwrite($fch, 'propel.database = '.$configuracion['orm.adapter'].PHP_EOL);
    fwrite($fch, 'propel.database.url = '.$dsn.PHP_EOL);
    fwrite($fch, 'propel.database.user = '.$configuracion['orm.user'].PHP_EOL);
    fwrite($fch, 'propel.database.password = '.$configuracion['orm.password'].PHP_EOL);
    fwrite($fch, 'propel.project.dir = '.  realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..').PHP_EOL);
    fwrite($fch, 'propel.conf.dir = '.realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'LEFconfig'.DIRECTORY_SEPARATOR.'propel'.PHP_EOL);
    fwrite($fch, 'propel.schema.dir = '.realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'LEFconfig'.DIRECTORY_SEPARATOR.'propel'.PHP_EOL);
    fwrite($fch, 'propel.output.dir = '.realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'LEFmodel'.PHP_EOL);

    fclose($fch);