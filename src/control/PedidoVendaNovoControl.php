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

class PedidoVendaNovoControl
{
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

        $serial = [];
        /** @var $orcamento OrcamentoVenda */
        foreach ($orcamentos as $orcamento) {
            $vinculo = (new PedidoVenda())->findByOrder($orcamento->getId());

            if (!$vinculo)
                $serial[] = $orcamento->jsonSerialize();
        }

        Banco::getInstance()->getConnection()->close();

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

    public function gravar(int $cli, int $orc, string $desc, int $vdd, int $cid, float $peso, float $valor, int $forma, float $valorRecebido, int $porcComissaoVendedor, array $comissoes, array $itens)
    {
        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar-se ao banco de dados.");

        $cliente = Cliente::getById($cli);
        $orcamento = ($orc > 0) ? OrcamentoVenda::findById($orc) : null;
        $fp = FormaPagamento::findById($forma);
        $vendedor = ($vdd > 0) ? Funcionario::getById($vdd) : null;

        $cidade = (new Cidade())->getById($cid);
        if (!$cidade)
            return json_encode("Cidade não encontrada no cadastro.");

        $usuario = Usuario::getById($_COOKIE["USER_ID"]);

        Banco::getInstance()->getConnection()->begin_transaction();

        $pedido = new PedidoVenda();
        $pedido->setData(date('Y-m-d'));
        $pedido->setDescricao($desc);
        $pedido->setPeso($peso);
        $pedido->setValor($valor);
        $pedido->setVendedor($vendedor);
        $pedido->setDestino($cidade);
        $pedido->setOrcamento($orcamento);
        $pedido->setCliente($cliente);
        $pedido->setFormaPagamento($fp);
        $pedido->setAutor($usuario);

        $res = $pedido->save();
        if ($res === -10 || $res === -1 || $res === 0) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Erro ao tentar gravar os dados do pedido.");
        }
        if ($res === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }

        $pedido->setId($res);
        for ($i = 0; $i < count($itens); $i++) {
            $produto = Produto::findById($itens[$i]->produto->id);
            $item = new ItemPedidoVenda($produto, $itens[$i]->quantidade, $itens[$i]->valor, $itens[$i]->peso);

            $ri = $item->save($res);
            if ($ri === -10 || $ri === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Erro ao tentar gravar os dados de um item do orçamento.");
            }
            if ($ri === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetros inválidos de um item do orçamento.");
            }
        }
        
        $rc = $this->lancarContas($pedido, $vendedor, $fp, $cliente, $usuario, $valor, $valorRecebido, $porcComissaoVendedor, $comissoes);
        if ($rc === -10 || $rc === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreram problemas ao lançar as contas.");
        }

        if ($rc === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Campos inválidos ou incorretos nas contas.");
        }
        
        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }

    /**
     * @param PedidoVenda $pedido
     * @param Funcionario|null $vendedor
     * @param FormaPagamento $forma
     * @param Cliente $cliente
     * @param Usuario $autor
     * @param float $valor
     * @param float $valorRecebido
     * @param int $porcComissaoVendedor
     * @param array $comissoes
     * @return int
     */
    private function lancarContas(PedidoVenda $pedido, ?Funcionario $vendedor, FormaPagamento $forma, Cliente $cliente, Usuario $autor, float $valor, float $valorRecebido, int $porcComissaoVendedor, array $comissoes): int
    {
        $response = 0;

        $situacao = 3;
        $valorLanc = $valorRecebido;
        if ($valorRecebido < $valor) {
            $valorLanc = $valor - $valorRecebido;
            $situacao = 2;
        }

        if ($vendedor) {
            $comissaoVendedor = $valor/100*$porcComissaoVendedor;
            $respostaComissaoVendador = $this->lancarComissaoVendedor($vendedor, $pedido, $autor, $comissaoVendedor);
            if ($respostaComissaoVendador <= 0) {
                return $respostaComissaoVendador;
            }
        }

        for ($i = 0; $i < count($comissoes); $i++) {
            $responseComissao = $this->lancarComissao($pedido, $autor, $comissoes[$i]);
            if ($responseComissao <= 0) {
                return $responseComissao;
            }
        }

        $conta = new ContaReceber();
        $conta->setConta($conta->findNewCount());
        $conta->setData(date("Y-m-d"));
        $conta->setDescricao("Recebimento pedido: " . $pedido->getId());
        $conta->setPagador(($cliente->getTipo() === 1) ? $cliente->getPessoaFisica()->getNome() : $cliente->getPessoaJuridica()->getNomeFantasia());
        $conta->setValor($valor);
        $conta->setComissao(false);
        $conta->setSituacao(1);
        $conta->setVencimento((new \DateTime())->add(new \DateInterval("P1M"))->format("Y-m-d"));
        $conta->setPedidoVenda($pedido);
        $conta->setAutor($autor);

        $response = $conta->save();
        if ($response <= 0) {
            return $response;
        } else {
            $conta->setId($response);
        }

        $responsePendente = 0;
        if ($situacao === 2) {
            $contaPendente = new ContaReceber();
            $contaPendente->setConta($conta->getConta());
            $contaPendente->setData(date("Y-m-d"));
            $contaPendente->setDescricao("Recebimento pedido: " . $pedido->getId() . " -> Pendência");
            $contaPendente->setPagador(($cliente->getTipo() === 1) ? $cliente->getPessoaFisica()->getNome() : $cliente->getPessoaJuridica()->getNomeFantasia());
            $contaPendente->setValor($valorLanc);
            $contaPendente->setComissao(false);
            $contaPendente->setSituacao(1);
            $contaPendente->setVencimento($conta->getVencimento());
            $contaPendente->setPedidoVenda($pedido);
            $contaPendente->setAutor($autor);

            $responsePendente = $contaPendente->save();
            if ($responsePendente <= 0) {
                return $responsePendente;
            }
        }

        $response = $conta->receber(
            $forma->getId(), $valorRecebido, date("Y-m-d"), $situacao, $responsePendente
        );

        return $response;
    }

    /**
     * @param Funcionario $vendedor
     * @param PedidoVenda $pedido
     * @param Usuario $autor
     * @param float $valor
     * @return int
     */
    private function lancarComissaoVendedor(Funcionario $vendedor, PedidoVenda $pedido, Usuario $autor, float $valor): int
    {
        $contaComissaoVendedor = new ContaPagar();
        $contaComissaoVendedor->setConta($contaComissaoVendedor->findNewCount());
        $contaComissaoVendedor->setData(date("Y-m-d"));
        $contaComissaoVendedor->setDescricao("Comissão vendedor: " . $vendedor->getPessoa()->getNome() . ". Pedido: " . $pedido->getId());
        $contaComissaoVendedor->setTipo(2);
        $contaComissaoVendedor->setEmpresa($vendedor->getPessoa()->getNome());
        $contaComissaoVendedor->setParcela(1);
        $contaComissaoVendedor->setValor($valor);
        $contaComissaoVendedor->setComissao(true);
        $contaComissaoVendedor->setSituacao(1);
        $contaComissaoVendedor->setVencimento((new \DateTime())->add(new \DateInterval("P2M"))->format("Y-m-d"));
        $contaComissaoVendedor->setCategoria(CategoriaContaPagar::findById(1));
        $contaComissaoVendedor->setVendedor($vendedor);
        $contaComissaoVendedor->setPedidoVenda($pedido);
        $contaComissaoVendedor->setAutor($autor);

        return $contaComissaoVendedor->save();
    }

    /**
     * @param PedidoVenda $pedido
     * @param Usuario $autor
     * @param $comissao
     * @return int
     */
    private function lancarComissao(PedidoVenda $pedido, Usuario $autor, $comissao) : int
    {
        $rep = Representacao::getById($comissao->representacao->id);
        $valor = $comissao->valor/100*$comissao->porcentagem;

        $conta = new ContaReceber();
        $conta->setConta($conta->findNewCount());
        $conta->setData(date("Y-m-d"));
        $conta->setDescricao("Recebimento comissão pedido: " . $pedido->getId());
        $conta->setPagador($comissao->representacao->nomeFantasia);
        $conta->setValor($valor);
        $conta->setComissao(true);
        $conta->setSituacao(1);
        $conta->setVencimento((new \DateTime())->add(new \DateInterval("P1M"))->format("Y-m-d"));
        $conta->setRepresentacao($rep);
        $conta->setPedidoVenda($pedido);
        $conta->setAutor($autor);

        return $conta->save();
    }
}