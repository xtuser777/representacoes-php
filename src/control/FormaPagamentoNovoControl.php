<?php


namespace scr\control;


use scr\model\FormaPagamento;
use scr\util\Banco;

class FormaPagamentoNovoControl
{
    public function gravar(string $descricao, int $vinculo, int $prazo)
    {
        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar-se ao banco de dados.");

        Banco::getInstance()->getConnection()->begin_transaction();

        $forma = new FormaPagamento(0, $descricao, $vinculo, $prazo);
        $res = $forma->save();
        if ($res == -10 || $res == -1 || $res == 0) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um erro ao salvar a forma de pagamento.");
        }
        if ($res == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}