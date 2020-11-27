<?php


namespace scr\control;


use scr\model\ContaPagar;
use scr\model\ContaReceber;
use scr\model\EtapaCarregamento;
use scr\model\Evento;
use scr\model\ItemPedidoFrete;
use scr\model\PedidoFrete;
use scr\model\Status;
use scr\model\StatusPedido;
use scr\model\Usuario;
use scr\util\Banco;

class PedidoFreteControl
{
    public function obter(int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findAll($this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorStatus(int $status, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByStatus($status, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltro(string $filtro, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByFilter($filtro, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroStatus(string $filtro, int $status, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByFilterStatus($filtro, $status, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodo(string $dataInicio, string $dataFim, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByPeriod($dataInicio, $dataFim, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoStatus(string $dataInicio, string $dataFim, int $status, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByPeriodStatus($dataInicio, $dataFim, $status, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodo(string $filtro, string $dataInicio, string $dataFim, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByFilterPeriod($filtro, $dataInicio, $dataFim, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoStatus(string $filtro, string $dataInicio, string $dataFim, int $status, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByFilterPeriodStatus($filtro, $dataInicio, $dataFim, $status, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function traduzirOrdem(int $ordem)
    {
        $ordemTraduzida = "";

        switch ($ordem) {
            case 1: $ordemTraduzida = "pfr.ped_fre_descricao"; break;
            case 2: $ordemTraduzida = "pfr.ped_fre_descricao DESC"; break;
            case 3: $ordemTraduzida = "pfr.ped_fre_data"; break;
            case 4: $ordemTraduzida = "pfr.ped_fre_data DESC"; break;
            case 5: $ordemTraduzida = "autor_pf.pf_nome"; break;
            case 6: $ordemTraduzida = "autor_pf.pf_nome DESC"; break;
            case 7: $ordemTraduzida = "fp.for_pag_descricao"; break;
            case 8: $ordemTraduzida = "fp.for_pag_descricao DESC"; break;
            case 9: $ordemTraduzida = "st.sts_descricao"; break;
            case 10: $ordemTraduzida = "st.sts_descricao DESC"; break;
            case 11: $ordemTraduzida = "pfr.ped_fre_valor"; break;
            case 12: $ordemTraduzida = "pfr.ped_fre_valor DESC"; break;
        }

        return $ordemTraduzida;
    }

    public function obterStatus()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $status = (new Status())->findAll();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $stat Status */
        foreach ($status as $stat) {
            $serial[] = $stat->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function enviar(int $id)
    {
        if ($id <= 0)
            return json_encode("Parâmetro inválido.");

        setcookie("PEDFRE", $id, time() + 3600, "/", "", 0, 1);

        return json_encode("");
    }

    public function excluir(int $id)
    {
        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar-se ao banco de dados.");

        $pedido = (new PedidoFrete())->findById($id);
        if (!$pedido)
            return json_encode("Registro não encontrado.");

        $usuario = Usuario::getById($_COOKIE["USER_ID"]);

        Banco::getInstance()->getConnection()->begin_transaction();

        if (!$this->excluirEtapas($pedido))
            return json_encode("Erro ao excluir as etapas de carregamento do pedido.");

        if (!$this->excluirItens($pedido))
            return json_encode("Erro ao excluir os itens do pedido.");

        if (!$this->excluirStatus($pedido))
            return json_encode("Erro ao excluir os status do frete.");

        if (!$this->excluirContaPedido($pedido))
            return json_encode("Erro ao excluir a conta a receber do pedido.");

        $cp = $this->excluirContaProprietario($pedido);
        if ($cp == -10 || $cp == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getconnection()->close();
            return json_encode("Ocorreu um problema ao excluir a conta a pagar do proprietário.");
        }
        if ($cp == -15) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getconnection()->close();
            return json_encode("A conta a pagar do proprietário está paga ou parcialmente paga, estorne esta antes de excluir.");
        }
        if ($cp == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getconnection()->close();
            return json_encode("Parâmetro inválido.");
        }

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

    public function excluirItens(PedidoFrete $pedido): bool
    {
        $itens = $pedido->getItens();
        if (count($itens) <= 0)
            return true;

        /** @var ItemPedidoFrete $item */
        foreach ($itens as $item) {
            $iv = $item->delete($pedido->getId(), $item->getProduto()->getId());
            if ($iv == -10 || $iv == -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return false;
            }
        }

        return true;
    }

    private function excluirEtapas(PedidoFrete $pedido): bool
    {
        $etapas = $pedido->getEtapas();
        if (count($etapas) <= 0)
            return true;

        /** @var EtapaCarregamento $etapa */
        foreach ($etapas as $etapa) {
            $res = $etapa->delete();
            if ($res < 0) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return false;
            }
        }

        return true;
    }

    private function excluirStatus(PedidoFrete $pedido): bool
    {
        $status = $pedido->getStatus()->findBySale($pedido->getId());
        if (count($status) <= 0)
            return true;

        /** @var StatusPedido $sp */
        foreach ($status as $sp) {
            $res = $sp->delete($pedido->getId());
            if ($res < 0) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return false;
            }
        }

        return true;
    }

    /**
     * @param PedidoFrete $pedido
     * @return int
     */
    private function excluirContaProprietario(PedidoFrete $pedido) : int
    {
        if ($pedido === null)
            return -5;

        $conta = (new ContaPagar())->findByDelivery($pedido->getId());
        if ($conta === null)
            return -10;

        if ($conta->getSituacao() > 1)
            return -15;

        return $conta->delete();
    }

    /**
     * @param PedidoFrete $pedido
     * @return bool
     */
    private function excluirContaPedido(PedidoFrete $pedido): bool
    {
        $conta = (new ContaReceber())->findByDelivery($pedido->getId());

        if ($conta === null)
            return true;

        $res = $conta->delete();
        if ($res < 0) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return false;
        }

        return true;
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
        $evento->setDescricao("Exclusão do pedido de frete ". $pedido->getId() . ": " . $pedido->getDescricao());
        $evento->setData(date("Y-m-d"));
        $evento->setHora(date("H:i:s"));
        $evento->setPedidoFrete($pedido);
        $evento->setAutor($usuario);

        return $evento->save();
    }
}