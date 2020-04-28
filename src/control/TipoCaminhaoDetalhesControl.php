<?php namespace scr\control;


use scr\model\TipoCaminhao;
use scr\util\Banco;

class TipoCaminhaoDetalhesControl
{
    public function obter()
    {
        if (!isset($_SESSION["TIPO"])) return json_encode(null);
        if (!Banco::getInstance()->open()) return json_encode(null);
        $tipo = TipoCaminhao::findById($_SESSION["TIPO"]);
        Banco::getInstance()->getConnection()->close();

        return json_encode($tipo ? $tipo->jsonSerialize() : null);
    }

    public function alterar(int $tip, string $descricao, int $eixos, float $capacidade)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");

        Banco::getInstance()->getConnection()->begin_transaction();

        $tipo = new TipoCaminhao($tip, $descricao, $eixos, $capacidade);
        $res = $tipo->update();
        if ($res == -10 || $res == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao alterar o tipo.");
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