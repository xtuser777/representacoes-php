<?php


namespace scr\control;


use scr\model\Caminhao;
use scr\model\CategoriaContaPagar;
use scr\model\Cidade;
use scr\model\Cliente;
use scr\model\ContaPagar;
use scr\model\ContaReceber;
use scr\model\EtapaCarregamento;
use scr\model\Evento;
use scr\model\FormaPagamento;
use scr\model\ItemPedidoFrete;
use scr\model\Motorista;
use scr\model\OrcamentoFrete;
use scr\model\PedidoFrete;
use scr\model\PedidoVenda;
use scr\model\Produto;
use scr\model\Proprietario;
use scr\model\Representacao;
use scr\model\Status;
use scr\model\StatusPedido;
use scr\model\TipoCaminhao;
use scr\model\Usuario;
use scr\util\Banco;

class PedidoFreteNovoControl
{
    public function obterOrcamentos()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $orcamentos = (new OrcamentoFrete())->findAll();

        $serial = [];
        /** @var OrcamentoFrete $orcamento */
        foreach ($orcamentos as $orcamento) {
            $vinculo = (new PedidoFrete())->findByOrder($orcamento->getId());

            if (!$vinculo)
                $serial[] = $orcamento->jsonSerialize();
        }

        Banco::getInstance()->getConnection()->close();

        return json_encode($serial);
    }

    public function obterVendas()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $vendas = (new PedidoVenda())->findAll();

        $serial = [];
        /** @var PedidoVenda $venda */
        foreach ($vendas as $venda) {
            $vinculo = (new PedidoFrete())->findByPrice($venda->getId());

            if (!$vinculo)
                $serial[] = $venda->jsonSerialize();
        }

        Banco::getInstance()->getConnection()->close();

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

    public function obterOrcamento(int $id)
    {
        if (!Banco::getInstance()->open())
            return json_encode(null);

        $orcamento = (new OrcamentoFrete())->findById($id);

        Banco::getInstance()->getConnection()->close();

        return json_encode($orcamento ? $orcamento->jsonSerialize() : null);
    }

    public function obterVenda(int $id)
    {
        if (!Banco::getInstance()->open())
            return json_encode(null);

        $venda = (new PedidoVenda())->findById($id);

        Banco::getInstance()->getConnection()->close();

        return json_encode($venda ? $venda->jsonSerialize() : null);
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

    public function obterCaminhoesPorPropTC(int $prop, int $tipo)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $caminhoes = Caminhao::findByProprietaryType($prop, $tipo);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Caminhao $caminhao */
        foreach ($caminhoes as $caminhao) {
            $serial[] = $caminhao->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function calcularPisoMinimo(float $distancia, int $eixos)
    {
        $piso = (new PedidoFrete())->calcularPisoMinimo($distancia, $eixos);

        return json_encode($piso);
    }

    public function gravar(int $orc, int $ven, int $rep, string $desc, int $cli, int $cid, int $tip, int $prop, int $cam, int $dist, int $mot, float $vm, float $va, int $fa, float $peso, float $valor, int $fr, string $entrega, array $itens, array $etapas)
    {
        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar-se ao banco de dados.");

        $orcamento = $orc > 0 ? (new OrcamentoFrete())->findById($orc) : null;
        $venda = $ven > 0 ? (new PedidoVenda())->findById($ven) : null;
        $representacao = $rep > 0 ? Representacao::getById($rep) : null;
        $cliente = Cliente::getById($cli);
        $cidade = (new Cidade())->getById($cid);
        $tipo = TipoCaminhao::findById($tip);
        $proprietario = (new Proprietario())->findById($prop);
        $caminhao = Caminhao::findById($cam);
        $motorista = Motorista::findById($mot);
        $formaAdiantamnto = $fa > 0 ? FormaPagamento::findById($fa) : null;
        $formaRecebimento = FormaPagamento::findById($fr);
        $usuario = Usuario::getById($_COOKIE["USER_ID"]);

        Banco::getInstance()->getConnection()->begin_transaction();

        $pedido = new PedidoFrete();
        $pedido->setData(date("y-m-d"));
        $pedido->setOrcamento($orcamento);
        $pedido->setVenda($venda);
        $pedido->setCliente($cliente);
        $pedido->setRepresentacao($representacao);
        $pedido->setDescricao($desc);
        $pedido->setTipoCaminhao($tipo);
        $pedido->setProprietario($proprietario);
        $pedido->setCaminhao($caminhao);
        $pedido->setDistancia($dist);
        $pedido->setDestino($cidade);
        $pedido->setMotorista($motorista);
        $pedido->setValorMotorista($vm);
        $pedido->setEntradaMotorista($va);
        $pedido->setFormaPagamentoMotorista($formaAdiantamnto);
        $pedido->setPeso($peso);
        $pedido->setValor($valor);
        $pedido->setFormaPagamentoFrete($formaRecebimento);
        $pedido->setEntrega($entrega);
        $pedido->setAutor($usuario);

        $res = $pedido->save();
        if ($res === -10 || $res === -1 || $res === 0) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Erro ao tentar abrir o novo pedido.");
        }
        if ($res === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }

        $pedido->setId($res);
        for ($i = 0; $i < count($itens); $i++) {
            $produto = Produto::findById($itens[$i]->produto->id);
            $itemPed = new ItemPedidoFrete($produto, $itens[$i]->quantidade, $itens[$i]->peso);

            $ri = $itemPed->save($res);
            if ($ri === -10 || $ri === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Erro ao tentar gravar os dados de um item do pedido.");
            }
            if ($ri === -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetros inválidos de um item do pedido.");
            }
        }

        $etapas = $this->adicionarEtapas($pedido, $etapas);
        if ($etapas === -10 || $etapas === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Erro ao tentar gravar os dados de uma etapa de carregamento do pedido.");
        }
        if ($etapas === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos de uma etapa de carregamento do pedido.");
        }

        $status = $this->adicionarStatus($res, $usuario);
        if ($status === -10 || $status === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Erro ao tentar gravar os dados do status do pedido.");
        }
        if ($status === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos do status do pedido.");
        }

        $contaProp = $this->lancarContaProprietario($pedido, $motorista, $usuario, $formaAdiantamnto, $vm, $va);
        if ($contaProp === -10 || $contaProp === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Erro ao tentar gravar os dados da conta do proprietário.");
        }
        if ($contaProp === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos da conta.");
        }

        $contaPed = $this->lancarContaPedido($pedido, $orcamento, $venda, $representacao, $formaRecebimento, $usuario, $valor);
        if ($contaPed === -10 || $contaPed === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Erro ao tentar gravar os dados da conta do pedido.");
        }
        if ($contaPed === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos da conta.");
        }

        $evento = $this->criarEvento($pedido, $usuario);
        if ($evento === -10 || $evento === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Erro ao tentar gravar os dados do evento.");
        }
        if ($evento === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos do evento.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }

    /**
     * @param PedidoFrete $pedido
     * @param array $etapas
     * @return int
     */
    private function adicionarEtapas(PedidoFrete $pedido, array $etapas): int
    {
        if ($pedido === null || count($etapas) === 0)
            return -5;

        $res = 9999;
        for ($i = 0; $i < count($etapas) && $res > 0; $i++) {
            $representacao = Representacao::getById($etapas[$i]->representacao->id);

            $etapa = new EtapaCarregamento();
            $etapa->setOrdem($etapas[$i]->ordem);
            $etapa->setRepresentacao($representacao);
            $etapa->setCarga($etapas[$i]->carga);
            $etapa->setStatus($etapas[$i]->status);

            $res = $etapa->save($pedido->getId());
        }

        return $res;
    }

    /**
     * @param int $pedido
     * @param Usuario $autor
     * @return int
     */
    private function adicionarStatus(int $pedido, Usuario $autor): int
    {
        if ($pedido <= 0 || $autor === null)
            return -5;

        $status = (new Status())->findById(1);

        $statusPedido = new StatusPedido();
        $statusPedido->setStatus($status);
        $statusPedido->setData(date("Y-m-d"));
        $statusPedido->setObservacoes("");
        $statusPedido->setAtual(true);
        $statusPedido->setAutor($autor);

        return $statusPedido->save($pedido);
    }

    /**
     * @param PedidoFrete $pedido
     * @param Motorista $motorista
     * @param Usuario $autor
     * @param FormaPagamento|null $forma
     * @param float $valor
     * @param float $adiantamento
     * @return int
     */
    private function lancarContaProprietario(PedidoFrete $pedido, Motorista $motorista, Usuario $autor, ?FormaPagamento $forma, float $valor, float $adiantamento): int
    {
        if ($pedido === null || $motorista === null || $autor === null || $valor <= 0)
            return -5;

        $situacao = 1;
        $pendente = 0.0;

        if ($adiantamento > 0) {
            $pendente = $valor - $adiantamento;
            $situacao = 2;
        }

        $mot = $motorista->getPessoa()->getNome();

        $conta = new ContaPagar();
        $conta->setConta($conta->findNewCount());
        $conta->setData(date("Y-m-d"));
        $conta->setDescricao("Pagamento ao motorista $mot.");
        $conta->setTipo(1);
        $conta->setEmpresa($mot);
        $conta->setParcela(1);
        $conta->setValor($valor);
        $conta->setComissao(false);
        $conta->setSituacao(1);
        $conta->setVencimento((new \DateTime())->add(new \DateInterval("P2D"))->format("Y-m-d"));
        $conta->setCategoria(CategoriaContaPagar::findById(249));
        $conta->setMotorista($motorista);
        $conta->setPedidoFrete($pedido);
        $conta->setAutor($autor);

        $retorno = $conta->save();
        if ($retorno <= 0)
            return $retorno;

        if ($pendente > 0) {
            $conta->setId($retorno);

            $pendencia = new ContaPagar();
            $pendencia->setConta($conta->getConta());
            $pendencia->setData(date("Y-m-d"));
            $pendencia->setDescricao("Pagamento ao motorista $mot (Pendência).");
            $pendencia->setTipo(1);
            $pendencia->setEmpresa($mot);
            $pendencia->setParcela(1);
            $pendencia->setValor($pendente);
            $pendencia->setComissao(false);
            $pendencia->setSituacao(1);
            $pendencia->setVencimento((new \DateTime())->add(new \DateInterval("P2D"))->format("Y-m-d"));
            $pendencia->setCategoria(CategoriaContaPagar::findById(249));
            $pendencia->setMotorista($motorista);
            $pendencia->setPedidoFrete($pedido);
            $pendencia->setAutor($autor);

            $retorno = $pendencia->save();
            if ($retorno <= 0)
                return $retorno;

            $retorno = $conta->quitar($forma->getId(), $adiantamento, date("Y-m-d"), $situacao, $retorno);
        }

        return $retorno;
    }

    /**
     * @param PedidoFrete $pedido
     * @param OrcamentoFrete|null $orcamento
     * @param PedidoVenda|null $venda
     * @param Representacao|null $representacao
     * @param FormaPagamento $forma
     * @param Usuario $autor
     * @param float $valor
     * @return int
     */
    private function lancarContaPedido(PedidoFrete $pedido, ?OrcamentoFrete $orcamento, ?PedidoVenda $venda, ?Representacao $representacao, FormaPagamento $forma, Usuario $autor, float $valor): int
    {
        if ($pedido===null||$forma===null||$autor===null||$valor<=0)
            return -5;

        $pagador = "";
        if ($orcamento) {
            $pagador = $orcamento->getDescricao();
        } elseif ($venda) {
            $pagador = $venda->getDescricao();
        } elseif ($representacao) {
            $pagador = $representacao->getPessoa()->getNomeFantasia();
        }

        $conta = new ContaReceber();
        $conta->setConta($conta->findNewCount());
        $conta->setData(date("Y-m-d"));
        $conta->setDescricao("Recebimento pedido: " . $pedido->getId());
        $conta->setPagador($pagador);
        $conta->setValor($valor);
        $conta->setComissao(false);
        $conta->setSituacao(1);
        $conta->setVencimento((new \DateTime())->add(new \DateInterval("P2D"))->format("Y-m-d"));
        if ($orcamento) {
            $conta->setRepresentacao($representacao);
        }
        $conta->setPedidoFrete($pedido);
        $conta->setAutor($autor);

        $retorno = $conta->save();
        if ($retorno <= 0)
            return $retorno;

        $conta->setId($retorno);

        $retorno = $conta->receber($forma->getId(), $valor, $conta->getData(), 3, 0);

        return $retorno;
    }

    /**
     * @param PedidoFrete $pedido
     * @param Usuario $usuario
     * @return int
     */
    private function criarEvento(PedidoFrete $pedido, Usuario $usuario): int
    {
        if ($pedido === null || $usuario === null)
            return -5;

        $evento = new Evento();
        $evento->setDescricao("Abertura do pedido de frete ". $pedido->getId() . ": " . $pedido->getDescricao());
        $evento->setData(date("Y-m-d"));
        $evento->setHora(date("H:i:s"));
        $evento->setPedidoFrete($pedido);
        $evento->setAutor($usuario);

        return $evento->save();
    }
}