<?php


namespace scr\control;


use scr\model\EtapaCarregamento;
use scr\model\Evento;
use scr\model\PedidoFrete;
use scr\model\Status;
use scr\model\StatusPedido;
use scr\model\Usuario;
use scr\util\Banco;

class PedidoAutorizarVisualizarControl
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

    public function autorizar(int $etapa, int $pedido)
    {
        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar no banco de dados.");

        $frete = (new PedidoFrete())->findById($pedido);

        $etapa = (new EtapaCarregamento())->findById($etapa, $pedido);
        if ($etapa === null)
            return json_encode("Registro não encontrado.");

        Banco::getInstance()->getConnection()->begin_transaction();

        $res = $etapa->autorize();
        if ($res === -10 || $res === -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao atualizar o registro da etapa.");
        }

        if ($res == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetro ou registro inválido.");
        }

        if ($frete->getStatus()->getStatus()->getId() === 1) {
            $sp = new StatusPedido();
            $sp->setStatus((new Status())->findById(2));
            $sp->setData(date("Y-m-d"));
            $sp->setObservacoes("");
            $sp->setAutor(Usuario::getById($_COOKIE["USER_ID"]));

            $res1 = $sp->save($frete->getId());
            if ($res1 === -10 || $res1 === -1) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Ocorreu um problema ao vincular o novo status.");
            }

            if ($res1 == -5) {
                Banco::getInstance()->getConnection()->rollback();
                Banco::getInstance()->getConnection()->close();
                return json_encode("Parâmetro ou registro inválido.");
            }
        }

        $ordem = $etapa->getOrdem();
        $descricao = $frete->getDescricao();

        $evento = new Evento();
        $evento->setDescricao("Autorização de carregamento da etapa $ordem do pedido de frete $descricao.");
        $evento->setData(date("y-m-d"));
        $evento->setHora(date("H:i:s"));
        $evento->setPedidoFrete($frete);
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