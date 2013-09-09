<?php

class defaultModule extends LEFmodule
{
    public function actionMain()
    {
        use_helper('Url');
        
        $this -> nombre = 'Diego Jesus';
        $this -> apellido = 'Hincapie Espinal';
        
        $busqueda = new Criteria();
        $resultados = Tabla1Peer::doSelect($busqueda);
    }
}
