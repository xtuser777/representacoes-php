<?php


namespace scr\control;


use scr\model\PedidoFrete;
use scr\model\Status;
use scr\util\Banco;

class PedidoAutorizarControl
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
            if ($pedido->getStatus()->getStatus()->getId() === 1 || $pedido->getStatus()->getStatus()->getId() === 2)
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
            if ($pedido->getStatus()->getStatus()->getId() === 1 || $pedido->getStatus()->getStatus()->getId() === 2)
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
            if ($pedido->getStatus()->getStatus()->getId() === 1 || $pedido->getStatus()->getStatus()->getId() === 2)
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
            if ($pedido->getStatus()->getStatus()->getId() === 1 || $pedido->getStatus()->getStatus()->getId() === 2)
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
            if ($pedido->getStatus()->getStatus()->getId() === 1 || $pedido->getStatus()->getStatus()->getId() === 2)
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
            if ($pedido->getStatus()->getStatus()->getId() === 1 || $pedido->getStatus()->getStatus()->getId() === 2)
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
            if ($pedido->getStatus()->getStatus()->getId() === 1 || $pedido->getStatus()->getStatus()->getId() === 2)
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
            if ($pedido->getStatus()->getStatus()->getId() === 1 || $pedido->getStatus()->getStatus()->getId() === 2)
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
        for ($i = 0; $i < 2; $i++) {
            /** @var Status $st */
            $st = $status[$i];
            $serial[] = $st->jsonSerialize();
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
}