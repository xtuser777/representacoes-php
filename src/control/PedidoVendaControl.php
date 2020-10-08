<?php


namespace scr\control;


use scr\model\ItemPedidoVenda;
use scr\model\PedidoFrete;
use scr\model\PedidoVenda;
use scr\util\Banco;

class PedidoVendaControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoVenda())->findAll();
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

    public function traduzirOrdem(int $ordem)
    {
        $ordemTraduzida = "";

        switch ($ordem) {
            case "1": $ordemTraduzida = "ped_ven_descricao"; break;
            case "2": $ordemTraduzida = "ped_ven_descricao DESC"; break;
            case "3": $ordemTraduzida = "pf.pf_nome, pj.pj_nome_fantasia"; break;
            case "4": $ordemTraduzida = "pf.pf_nome, pj.pj_nome_fantasia DESC"; break;
            case "5": $ordemTraduzida = "ped_ven_data"; break;
            case "6": $ordemTraduzida = "ped_ven_data DESC"; break;
            case "7": $ordemTraduzida = "autor_pf.pf_nome"; break;
            case "8": $ordemTraduzida = "autor_pf.pf_nome DESC"; break;
            case "9": $ordemTraduzida = "fp.for_pag_descricao"; break;
            case "10": $ordemTraduzida = "fp.for_pag_descricao DESC"; break;
            case "11": $ordemTraduzida = "ped_ven_valor"; break;
            case "12": $ordemTraduzida = "ped_ven_valor DESC"; break;
        }

        return $ordemTraduzida;
    }

    public function enviar(int $id)
    {
        if ($id <= 0)
            return json_encode("Parâmetro inválido.");

        Banco::getInstance()->open();

        $frete = (new PedidoFrete())->findByPrice($id);
        if ($frete !== null)
            return json_encode("Este orçamento está vinculado ao pedido de frete \"" . $frete->getDescricao() . "\"");

        Banco::getInstance()->getConnection()->close();

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

        Banco::getInstance()->getConnection()->begin_transaction();

        if (!$this->excluirItens($id))
            return json_encode("Erro ao excluir os itens do pedido.");

        $ped = $pedido->delete();
        if ($ped == -10 || $ped == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getconnection()->close();
            return json_encode("Ocorreu um problema ao excluir o orçamento.");
        }
        if ($ped == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getconnection()->close();
            return json_encode("Parâmetro inválido.");
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
}