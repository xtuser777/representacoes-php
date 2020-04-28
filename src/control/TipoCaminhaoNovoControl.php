<?php namespace scr\control;

use scr\model\TipoCaminhao;
use scr\util\Banco;

class TipoCaminhaoNovoControl
{
    public function gravar(string $descricao, int $eixos, float $capacidade)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");

        Banco::getInstance()->getConnection()->begin_transaction();

        $tipo = new TipoCaminhao(0, $descricao, $eixos, $capacidade);
        $tip = $tipo->save();
        if ($tip == -10 || $tip == -1 || $tip == 0) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um erro ao salvar o tipo de caminhão.");
        }
        if ($tip == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros inválidos.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}