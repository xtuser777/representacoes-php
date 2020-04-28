<?php namespace scr\control;

use scr\util\Banco;
use scr\model\Estado;

class EstadoControl 
{
    public function getById(int $id)
    {
        $json = null;
        if (Banco::getInstance()->open())
        {
            $estado = Estado::getById($id);
            Banco::getInstance()->getConnection()->close();
            if ($estado) $json = $estado->jsonSerialize();
        }

        return json_encode($json, JSON_UNESCAPED_UNICODE);
    }
    
    public function getAll()
    {
        $jarray = array();
        if (Banco::getInstance()->open())
        {
            $array = Estado::getAll();
            Banco::getInstance()->getConnection()->close();

            for ($i = 0; $i < count($array); $i++)
            {
                /** @var Estado $e */
                $e = $array[$i];
                $jarray[] = $e->jsonSerialize();
            }
        }

        return json_encode($jarray, JSON_UNESCAPED_UNICODE);
    }
}
