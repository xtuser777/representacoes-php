<?php

namespace scr\control;

use scr\model\Produto;
use scr\util\Banco;

class OrcamentoVendaNovoItemControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $produtos = Produto::findAll();
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var Produto $produto */
        foreach ($produtos as $produto) {
            $serial[] = $produto->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltro(string $filtro)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $produtos = Produto::findByKey($filtro);
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var Produto $produto */
        foreach ($produtos as $produto) {
            $serial[] = $produto->jsonSerialize();
        }

        return json_encode($serial);
    }
}