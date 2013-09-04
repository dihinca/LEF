<?php

class defaultModule extends LEFmodule
{
    public function actionMain()
    {
        $busqueda = new Criteria();
        $resultados = Tabla1Peer::doSelect($busqueda);
    }
}
