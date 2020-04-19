<?php namespace scr\control;

use scr\util\Banco;
use scr\model\Estado;

class EstadoControl 
{
    public function getById(int $id)
    {
        $db = Banco::getInstance();
        $db->open();
        if ($db->getConnection() == null) return json_encode(null);
        $estado = Estado::getById($db->getConnection(), $id)->jsonSerialize();
        $db->getConnection()->close();

        return json_encode($estado, JSON_UNESCAPED_UNICODE);
    }
    
    public function getAll()
    {
        $db = Banco::getInstance();
        $db->open();
        if ($db->getConnection() == null) return json_encode(null);
        $list = Estado::getAll($db->getConnection());
        $db->getConnection()->close();
        
        $jlist = [];
        for ($i = 0; $i < count($list); $i++)
        {
            /** @var Estado $e */
            $e = $list[$i];
            $jlist[] = $e->jsonSerialize();             
        }
        
        return json_encode($jlist, JSON_UNESCAPED_UNICODE);
    }
}
