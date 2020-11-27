<?php


namespace scr\control;


use scr\model\EtapaCarregamento;
use scr\model\ItemPedidoFrete;
use scr\model\Produto;
use scr\model\TipoCaminhao;
use scr\util\Banco;

class PedidoFreteDetalhesItemControl
{
    public function obter()
    {
        if (!isset($_COOKIE["PEDFRE"]))
            return json_encode("Pedido não selecionado.");

        if (!Banco::getInstance()->open())
            return json_encode([]);

        $itens = (new ItemPedidoFrete())->findBySale($_COOKIE["PEDFRE"]);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var ItemPedidoFrete $item */
        foreach ($itens as $item) {
            $serial[] = $item->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterEtapas()
    {
        if (!isset($_COOKIE["PEDFRE"]))
            return json_encode("Pedido não selecionado.");

        if (!Banco::getInstance()->open())
            return json_encode([]);

        $etapas = (new EtapaCarregamento())->findBySale($_COOKIE["PEDFRE"]);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var EtapaCarregamento $etapa */
        foreach ($etapas as $etapa) {
            $serial[] = $etapa->jsonSerialize();
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