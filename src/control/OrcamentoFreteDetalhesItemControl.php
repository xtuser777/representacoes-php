<?php


namespace scr\control;


use scr\model\ItemOrcamentoFrete;
use scr\model\ItemOrcamentoVenda;
use scr\model\Produto;
use scr\model\TipoCaminhao;
use scr\util\Banco;

class OrcamentoFreteDetalhesItemControl
{
    public function obter(int $orc)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $itens = (new ItemOrcamentoFrete())->findAll($orc);
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var ItemOrcamentoFrete $item */
        foreach ($itens as $item) {
            $serial[] = $item->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorRepresentacao(int $rep)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $produtos = Produto::findByRepresentation($rep);
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var Produto $produto */
        foreach ($produtos as $produto) {
            $serial[] = $produto->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorRepresentacaoFiltro(int $rep, string $filtro)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $produtos = Produto::findByKeyRepresentation($filtro, $rep);
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var Produto $produto */
        foreach ($produtos as $produto) {
            $serial[] = $produto->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorVenda(int $venda)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $itens = ItemOrcamentoVenda::findAllItems($venda);
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var ItemOrcamentoVenda $item */
        foreach ($itens as $item) {
            $serial[] = $item->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterTiposPorItem(int $item)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
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