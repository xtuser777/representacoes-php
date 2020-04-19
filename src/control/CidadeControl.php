<?php namespace scr\control;

use scr\util\Banco;
use scr\model\Cidade;

class CidadeControl 
{
    public function getById(int $id) 
    {
        $db = Banco::getInstance();
        $db->open();
        if ($db->getConnection() == null) return json_encode(null);
        $cidade = Cidade::getById($db->getConnection(), $id)->jsonSerializer();
        $db->getConnection()->close();

        return json_encode($cidade, JSON_UNESCAPED_UNICODE);
    }
    
    public function getByEstado(int $estado)
    {
        $db = Banco::getInstance();
        $db->open();
        if ($db->getConnection() == null) return json_encode(array());
        $list = Cidade::getByEstado($db->getConnection(), $estado);
        $db->getConnection()->close();
        
        $jlist = [];
        for ($i = 0; $i < count($list); $i++)
        {
            /** @var Cidade $c */
            $c = $list[$i];
            $jlist[] = $c->jsonSerialize();
        }
        
        return json_encode($jlist, JSON_UNESCAPED_UNICODE);
    }
}
