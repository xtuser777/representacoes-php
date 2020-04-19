<?php namespace scr\control;

use scr\model\Representacao;

class RepresentacaoControl
{
    public function obter()
    {
        $array = Representacao::getAll();
        $jarray = array();
        for ($i = 0; $i < count($array); $i++) {
            /** @var Representacao $r */
            $r = $array[$i];
            $jarray[] = $r->jsonSerialize();
        }
        
        return json_encode($jarray);
    }
}