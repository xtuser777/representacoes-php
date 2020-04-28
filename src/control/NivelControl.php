<?php namespace scr\control;

use scr\util\Banco;
use scr\model\Nivel;

class NivelControl 
{
    public function getAll() 
    {
        $jarray = [];
        if (Banco::getInstance()->open())
        {
            $array = Nivel::getAll();
            Banco::getInstance()->getConnection()->close();

            for ($i = 0; $i < count($array); $i++) {
                /** @var Nivel $n */
                $n = $array[$i];
                $jarray[] = $n->jsonSerialize();
            }
        }
        
        return json_encode($jarray);
    }
}
