<?php


namespace scr\control;


use scr\model\CategoriaContaPagar;
use scr\model\Cidade;
use scr\model\Cliente;
use scr\model\ContaPagar;
use scr\model\ContaReceber;
use scr\model\FormaPagamento;
use scr\model\Funcionario;
use scr\model\ItemOrcamentoVenda;
use scr\model\ItemPedidoVenda;
use scr\model\OrcamentoVenda;
use scr\model\PedidoVenda;
use scr\model\Produto;
use scr\model\Representacao;
use scr\model\Usuario;
use scr\util\Banco;

class PedidoVendaDetalhesControl
{
    public function obter()
    {
        if (!isset($_COOKIE["PEDVEN"]))
            return json_encode(null);

        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedido = (new PedidoVenda())->findById($_COOKIE["PEDVEN"]);

        Banco::getInstance()->getConnection()->close();

        return json_encode($pedido ? $pedido->jsonSerialize() : null);
    }

    public function obterClientes()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $clientes = Cliente::getAll();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Cliente $cliente */
        foreach ($clientes as $cliente) {
            $serial[] = $cliente->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterVendedores()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $vendedores = Funcionario::getVendedores();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Funcionario $vendedor */
        foreach ($vendedores as $vendedor) {
            $serial[] = $vendedor->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterFormas()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $formas = FormaPagamento::findByReceive();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $forma FormaPagamento */
        foreach ($formas as $forma) {
            $serial[] = $forma->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterOrcamentos()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $orcamentos = OrcamentoVenda::findAll();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $orcamento OrcamentoVenda */
        foreach ($orcamentos as $orcamento) {
            $serial[] = $orcamento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterOrcamento(int $id)
    {
        if (!Banco::getInstance()->open())
            return json_encode(null);

        $orcamento = OrcamentoVenda::findById($id);

        Banco::getInstance()->getConnection()->close();

        return json_encode(($orcamento) ? $orcamento->jsonSerialize() : null);
    }

    public function obterItensPorOrcamento(int $orc)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $itens = ItemOrcamentoVenda::findAllItems($orc);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $item ItemOrcamentoVenda */
        foreach ($itens as $item) {
            $serial[] = $item->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterComissaoVendedor(int $pedido)
    {
        if (!Banco::getInstance()->open())
            return json_encode(null);

        $comissao = (new ContaPagar())->findComissionBySale($pedido);

        Banco::getInstance()->getConnection()->close();

        return json_encode($comissao ? $comissao->jsonSerialize() : null);
    }

    public function obterComissoesVenda(int $pedido)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $comissoes = (new ContaReceber())->findComissionsBySale($pedido);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $comissao ContaReceber */
        foreach ($comissoes as $comissao) {
            $serial[] = $comissao->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterRecebimentoVenda(int $pedido)
    {
        if (!Banco::getInstance()->open())
            return json_encode(null);

        $receive = (new ContaReceber())->findReceiveBySale($pedido);

        Banco::getInstance()->getConnection()->close();

        return json_encode($receive ? $receive->jsonSerialize() : null);
    }
}