<?php


namespace scr\control;


use scr\model\Caminhao;
use scr\model\Cliente;
use scr\model\FormaPagamento;
use scr\model\Motorista;
use scr\model\OrcamentoFrete;
use scr\model\PedidoFrete;
use scr\model\PedidoVenda;
use scr\model\Proprietario;
use scr\model\Representacao;
use scr\util\Banco;

class PedidoFreteDetalhesControl
{
    public function obter()
    {
        if (!isset($_COOKIE["PEDFRE"]))
            return json_encode("Pedido não selecionado.");

        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar no banco de dados.");

        $pedido = (new PedidoFrete())->findById($_COOKIE["PEDFRE"]);

        Banco::getInstance()->getConnection()->close();

        return json_encode($pedido ? $pedido->jsonSerialize() : null);
    }

    public function obterOrcamentos()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $orcamentos = (new OrcamentoFrete())->findAll();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var OrcamentoFrete $orcamento */
        foreach ($orcamentos as $orcamento) {
            $vinculo = (new PedidoFrete())->findByOrder($orcamento->getId());

            if (!$vinculo)
                $serial[] = $orcamento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterVendas()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $vendas = (new PedidoVenda())->findAll();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var PedidoVenda $venda */
        foreach ($vendas as $venda) {
            $vinculo = (new PedidoFrete())->findByPrice($venda->getId());

            if (!$vinculo)
                $serial[] = $venda->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterRepresentacoes()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $representacoes = Representacao::getAll();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Representacao $representacao */
        foreach ($representacoes as $representacao) {
            $serial[] = $representacao->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterClientes()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $clientes = Cliente::getAll();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Cliente $cli */
        foreach ($clientes as $cli) {
            $serial[] = $cli->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterMotoristas()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $motoristas = Motorista::findAll();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Motorista $mot */
        foreach ($motoristas as $mot) {
            $serial[] = $mot->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterFormasPagamento()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $fps = FormaPagamento::findByPayment();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var FormaPagamento $fp */
        foreach ($fps as $fp) {
            $serial[] = $fp->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterFormasRecebimento()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $fps = FormaPagamento::findByReceive();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var FormaPagamento $fp */
        foreach ($fps as $fp) {
            $serial[] = $fp->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterProprietariosPorTipo(int $tipo)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $props = (new Proprietario())->findByType($tipo);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Proprietario $prop */
        foreach ($props as $prop) {
            $serial[] = $prop->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterCaminhoesPorProp(int $prop)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $caminhoes = Caminhao::findByProprietary($prop);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Caminhao $caminhao */
        foreach ($caminhoes as $caminhao) {
            $serial[] = $caminhao->jsonSerialize();
        }

        return json_encode($serial);
    }
}