<?php


namespace scr\control;


use scr\model\CategoriaContaPagar;
use scr\util\Banco;

class CategoriaDetalhesControl
{
    public function obter()
    {
        if (!isset($_COOKIE["CAT"]))
            return json_encode(null);

        if (!Banco::getInstance()->open())
            return json_encode(null);

        $categoria = CategoriaContaPagar::findById($_COOKIE["CAT"]);

        Banco::getInstance()->getConnection()->close();

        return json_encode($categoria != null ? $categoria->jsonSerialize() : null);
    }

    public function alterar(int $cat, string $descricao)
    {
        if (!Banco::getInstance()->open())
            return json_encode("Erro ao conectar-se ao banco de dados.");

        Banco::getInstance()->getConnection()->begin_transaction();

        $categoria = new CategoriaContaPagar($cat, $descricao);
        $res = $categoria->update();
        if ($res == -10 || $res == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao alterar a categoria.");
        }
        if ($res == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetros incorretos.");
        }
        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}