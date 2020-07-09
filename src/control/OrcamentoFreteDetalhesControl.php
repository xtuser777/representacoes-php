<?php


namespace scr\control;


use scr\model\OrcamentoFrete;
use scr\model\OrcamentoVenda;
use scr\model\Representacao;
use scr\util\Banco;

class OrcamentoFreteDetalhesControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open()) return json_encode(null);
        $orcamento = (new OrcamentoFrete())->findById($_SESSION["ORCFRE"]);
        Banco::getInstance()->getConnection()->close();

        return json_encode(($orcamento !== null) ? $orcamento->jsonSerialize() : null);
    }

    public function obterVendas()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $vendas = OrcamentoVenda::findAll();
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var OrcamentoVenda $venda */
        foreach ($vendas as $venda) {
            $serial[] = $venda->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterRepresentacoes()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $representacoes = Representacao::getAll();
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var Representacao $representacao */
        foreach ($representacoes as $representacao) {
            $serial[] = $representacao->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function calcularPisoMinimo(float $distancia, int $eixos)
    {
        $piso = (new OrcamentoFrete())->calcularPisoMinimo($distancia, $eixos);

        return json_encode($piso);
    }
}