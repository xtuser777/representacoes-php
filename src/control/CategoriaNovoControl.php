<?php


namespace scr\control;


use scr\model\CategoriaContaPagar;
use scr\util\Banco;

class CategoriaNovoControl
{
    public function gravar(string $descricao)
    {
        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar-se ao banco de dados.");

        Banco::getInstance()->getConnection()->begin_transaction();

        $categoria = new CategoriaContaPagar(0, $descricao);
        $res = $categoria->save();
        if ($res == -10 || $res == -5 || $res == 0) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao alvar a categoria.");
        }
        if ($res == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetro inválido.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}