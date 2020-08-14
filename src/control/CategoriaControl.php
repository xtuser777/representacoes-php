<?php


namespace scr\control;


use scr\dao\CategoriaDAO;
use scr\model\Categoria;
use scr\util\Banco;

class CategoriaControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $categorias = CategoriaDAO::select();
        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var Categoria $categoria */
        foreach ($categorias as $categoria) {
            $jarray[] = $categoria->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorChave(string $chave)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $categorias = CategoriaDAO::selectkey($chave);
        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var Categoria $categoria */
        foreach ($categorias as $categoria) {
            $jarray[] = $categoria->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function ordenar(string $col)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $categorias = CategoriaDAO::select();
        Banco::getInstance()->getConnection()->close();

        if (count($categorias) > 0) {
            switch ($col) {
                case "1":
                    usort($categorias, function (Categoria $a, Categoria $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() < $b->getId()) ? -1 : 1);
                    });
                    break;
                case "2":
                    usort($categorias, function (Categoria $a, Categoria $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() > $b->getId()) ? -1 : 1);
                    });
                    break;
                case "3":
                    usort($categorias, function (Categoria $a, Categoria $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) < 0) ? -1 : 1);
                    });
                    break;
                case "4":
                    usort($categorias, function (Categoria $a, Categoria $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) > 0) ? -1 : 1);
                    });
                    break;
            }
        }

        $jarray = [];
        /** @var Categoria $categoria */
        foreach ($categorias as $categoria) {
            $jarray[] = $categoria->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function enviar(int $id)
    {
        if ($id <= 0) return json_encode('Parâmetro inválido.');
        setcookie('CAT', $id, time() + (3600), "/", "", 0, 1);

        return json_encode('');
    }

    public function excluir(int $id)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados,");

        $cat = Categoria::findById($id);

        Banco::getInstance()->getConnection()->begin_transaction();

        $res = $cat->delete();
        if ($res == -10 || $res == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um erro ao excluir a categoria.");
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