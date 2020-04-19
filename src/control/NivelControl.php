<?php namespace scr\control;

use scr\util\Banco;
use scr\model\Nivel;

class NivelControl 
{
    public function getAll() 
    {
        $db = Banco::getInstance();
        $db->open();
        $array = Nivel::getAll($db->getConnection());
        $db->getConnection()->close();

        $jarray = array();
        for ($i = 0; $i < count($array); $i++) {
            /** @var Nivel $n */
            $n = $array[$i];
            $jarray[] = $n->jsonSerialize();
        }
        
        return json_encode($jarray);
    }
}
