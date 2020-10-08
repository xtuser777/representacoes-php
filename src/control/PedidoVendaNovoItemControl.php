<?php


namespace scr\control;


use scr\model\Produto;
use scr\model\TipoCaminhao;
use scr\util\Banco;

class PedidoVendaNovoItemControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

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
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $produtos = Produto::findByKey($filtro);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Produto $produto */
        foreach ($produtos as $produto) {
            $serial[] = $produto->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterTiposPorItem(int $item)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $tipos = Produto::findById($item)->getTipos();

        if ($tipos === null || count($tipos) === 0) {
            $tipos = TipoCaminhao::findAll();
        }

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var TipoCaminhao $tipo */
        foreach ($tipos as $tipo) {
            $serial[] = $tipo->jsonSerialize();
        }

        return json_encode($serial);
    }
}