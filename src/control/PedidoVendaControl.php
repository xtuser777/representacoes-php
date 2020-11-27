<?php


namespace scr\control;


use scr\model\ContaPagar;
use scr\model\ContaReceber;
use scr\model\Evento;
use scr\model\ItemPedidoVenda;
use scr\model\PedidoFrete;
use scr\model\PedidoVenda;
use scr\model\Usuario;
use scr\util\Banco;

class PedidoVendaControl
{
    public function obter(int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoVenda())->findAll($this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoVenda */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltro(string $filtro, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoVenda())->findByFilter($filtro, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoVenda */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorData(string $data, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoVenda())->findByDate($data, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoVenda */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriod(string $dataInicio, string $dataFim, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoVenda())->findByPeriod($dataInicio, $dataFim, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoVenda */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroData(string $filtro, string $data, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoVenda())->findByFilterDate($filtro, $data, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoVenda */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriod(string $filtro, string $dataInicio, string $dataFim, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoVenda())->findByFilterPeriod($filtro, $dataInicio, $dataFim, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoVenda */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function traduzirOrdem(int $ordem)
    {
        $ordemTraduzida = "";

        switch ($ordem) {
            case 1: $ordemTraduzida = "pv.ped_ven_descricao"; break;
            case 2: $ordemTraduzida = "pv.ped_ven_descricao DESC"; break;
            case 3: $ordemTraduzida = "pf.pf_nome, pj.pj_nome_fantasia"; break;
            case 4: $ordemTraduzida = "pf.pf_nome DESC, pj.pj_nome_fantasia DESC"; break;
            case 5: $ordemTraduzida = "pv.ped_ven_data"; break;
            case 6: $ordemTraduzida = "pv.ped_ven_data DESC"; break;
            case 7: $ordemTraduzida = "autor_pf.pf_nome"; break;
            case 8: $ordemTraduzida = "autor_pf.pf_nome DESC"; break;
            case 9: $ordemTraduzida = "fp.for_pag_descricao"; break;
            case 10: $ordemTraduzida = "fp.for_pag_descricao DESC"; break;
            case 11: $ordemTraduzida = "pv.ped_ven_valor"; break;
            case 12: $ordemTraduzida = "pv.ped_ven_valor DESC"; break;
        }

        return $ordemTraduzida;
    }

    public function enviar(int $id)
    {
        if ($id <= 0)
            return json_encode("Parâmetro inválido.");

        setcookie("PEDVEN", $id, time() + 3600, "/", "", 0 , 1);

        return json_encode("");
    }

    public function excluir(int $id)
    {
        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar-se ao banco de dados.");

        $frete = (new PedidoFrete())->findByPrice($id);
        if ($frete !== null)
            return json_encode("Este orçamento está vinculado ao pedido de frete \"" . $frete->getDescricao() . "\"");

        $pedido = (new PedidoVenda())->findById($id);
        if ($pedido === null)
            return json_encode("Registro não encontrado.");

        $usuario = Usuario::getById($_COOKIE["USER_ID"]);

        Banco::getInstance()->getConnection()->begin_transaction();

        $contas = $this->deletarContas($pedido);
        if ($contas === -10) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreram problemas na exclusão das comissões do pedido ou algum registronão foi encontrado.");
        }

        if ($contas === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Algum parâmetro foi passado incorretamente.");
        }

        if ($contas === -15) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Não foi possível deletar uma comissao ou pendência que já tenha sido recebida.");
        }

        if (!$this->excluirItens($id))
            return json_encode("Erro ao excluir os itens do pedido.");

        $ped = $pedido->delete();
        if ($ped == -10 || $ped == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getconnection()->close();
            return json_encode("Ocorreu um problema ao excluir o pedido.");
        }
        if ($ped == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getconnection()->close();
            return json_encode("Parâmetro inválido.");
        }

        $re = $this->criarEvento($pedido, $usuario);
        if ($re === -10 || $re === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreram problemas ao criar o evento.");
        }

        if ($re === -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Campos inválidos ou incorretos no evento.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getconnection()->close();

        return json_encode("");
    }

    public function excluirItens(int $pedido): bool
    {
        $itens = (new ItemPedidoVenda())->findByPrice($pedido);

        if (count($itens) <= 0)
            return true;

        /** @var ItemPedidoVenda $item */
        foreach ($itens as $item) {
            $iv = $item->delete($pedido);
            if ($iv == -10 || $iv == -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return false;
            }
        }

        return true;
    }

    /**
     * @param int $pedido
     * @return int
     */
    private function deletarContas(PedidoVenda $pedido): int
    {
        $response = 0;

        if ($pedido->getId() <= 0)
            return -5;

        $contaPedido = (new ContaReceber())->findReceiveBySale($pedido->getId());
        if (!$contaPedido)
            return -10;

        if ($contaPedido->getPendencia() && $contaPedido->getPendencia()->getValorRecebido() > 0)
            return -15;

        if ($contaPedido->getPendencia()) {
            $response = $contaPedido->getPendencia()->delete();
            if ($response < 0)
                return $response;
        }

        $response = $contaPedido->delete();
        if ($response < 0)
            return $response;

        if ($pedido->getVendedor()) {
            $contaComissaoVendedor = (new ContaPagar())->findComissionBySale($pedido->getId());
            if (!$contaComissaoVendedor)
                return -10;

            if ($contaComissaoVendedor->getSituacao() > 1)
                return -15;

            $response = $contaComissaoVendedor->delete();
            if ($response < 0)
                return $response;
        }

        $comissoes = (new ContaReceber())->findComissionsBySale($pedido->getId());
        if (!$comissoes || count($comissoes) === 0)
            return -10;

        /** @var $comissao ContaReceber */
        foreach ($comissoes as $comissao) {
            if ($comissao->getSituacao() > 1)
                return -15;

            $response = $comissao->delete();
            if ($response < 0)
                return $response;
        }

        return $response;
    }

    /**
     * @param PedidoVenda $pedido
     * @param Usuario $usuario
     * @return int
     */
    private function criarEvento(PedidoVenda $pedido, Usuario $usuario): int
    {
        if ($pedido === null || $usuario === null)
            return -5;

        $evento = new Evento();
        $evento->setDescricao("O pedido de venda ". $pedido->getId() . " foi deletado.");
        $evento->setData(date("Y-m-d"));
        $evento->setHora(date("H:i:s"));
        $evento->setPedidoVenda($pedido);
        $evento->setAutor($usuario);

        return $evento->save();
    }
}