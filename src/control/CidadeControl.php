<?php


namespace scr\control;


use scr\util\Banco;
use scr\model\Cidade;

class CidadeControl 
{
    public function getById(int $id) 
    {
        $json = null;
        if (Banco::getInstance()->open())
        {
            $cidade = (new Cidade)->getById($id);
            Banco::getInstance()->getConnection()->close();
            if ($cidade) $json = $cidade->jsonSerialize();
        }

        return json_encode($json, JSON_UNESCAPED_UNICODE);
    }
    
    public function getByEstado(int $estado)
    {
        $jarray = array();
        if (Banco::getInstance()->open())
        {
            $array = (new Cidade)->getByEstado($estado);
            Banco::getInstance()->getConnection()->close();

            for ($i = 0; $i < count($array); $i++)
            {
                /** @var Cidade $c */
                $c = $array[$i];
                $jarray[] = $c->jsonSerialize();
            }
        }

        return json_encode($jarray, JSON_UNESCAPED_UNICODE);
    }
}
