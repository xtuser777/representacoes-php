<?php namespace scr\control;

use scr\model\FormaPagamento;
use scr\util\Banco;

class FormaPagamentoDetalhesControl
{
    public function obter()
    {
        if (!isset($_SESSION["FORMA"])) return json_encode(null);
        if (!Banco::getInstance()->open()) return json_encode(null);
        $forma = FormaPagamento::findById($_SESSION["FORMA"]);
        Banco::getInstance()->getConnection()->close();

        return json_encode($forma !== null ? $forma->jsonSerialize() : null);
    }

    public function alterar(int $for, string $descricao, int $prazo)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");
        Banco::getInstance()->getConnection()->begin_transaction();
        $forma = new FormaPagamento($for, $descricao, $prazo);
        $res = $forma->update();
        if ($res == -10 || $res == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao alterar a forma de pagamento.");
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