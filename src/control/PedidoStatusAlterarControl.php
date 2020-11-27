<?php


namespace scr\control;


use scr\model\Evento;
use scr\model\PedidoFrete;
use scr\model\Status;
use scr\model\StatusPedido;
use scr\model\Usuario;
use scr\util\Banco;

class PedidoStatusAlterarControl
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

    public function obterStatusAnterior()
    {
        if (!isset($_COOKIE["PEDFRE"]))
            return json_encode("Pedido não selecionado.");

        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar no banco de dados.");

        $sps = (new StatusPedido())->findBySale($_COOKIE["PEDFRE"]);

        Banco::getInstance()->getConnection()->close();

        $sp = $sps[count($sps)-2];

        return json_encode($sp ? $sp->jsonSerialize() : null);
    }

    public function gravar(int $ped, int $sts, string $data, string $obs)
    {
        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar no banco de dados.");

        $pedido = (new PedidoFrete())->findById($ped);

        $status = (new Status())->findById($sts);

        $sp = new StatusPedido();
        $sp->setStatus($status);
        $sp->setData($data);
        $sp->setObservacoes($obs);
        $sp->setAutor(Usuario::getById($_COOKIE["USER_ID"]));

        $res = $sp->save($pedido->getId());
        if ($res === -10 || $res === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao atualizar o registro do vículo do status.");
        }

        if ($res == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetro ou registro inválido.");
        }

        $desc = $status->getDescricao();

        $evento = new Evento();
        $evento->setDescricao("Alteração do status do pedido $ped para $desc.");
        $evento->setData(date("y-m-d"));
        $evento->setHora(date("H:i:s"));
        $evento->setPedidoFrete($pedido);
        $evento->setAutor(Usuario::getById($_COOKIE["USER_ID"]));

        $res2 = $evento->save();
        if ($res2 === -10 || $res2 === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao criar o evento.");
        }

        if ($res2 == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetro ou registro inválido.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}