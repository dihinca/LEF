<?php

class defaultModule extends LEFmodule
{
    public function actionMain()
    {
        $this -> nombre = 'Diego';
        $this -> apellido = 'Hincapie';
    }
}
