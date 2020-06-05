<?php


namespace scr\control;


use scr\model\Produto;
use scr\model\TipoCaminhao;
use scr\util\Banco;

class ProdutoTipoCaminhaoControl
{
    public function obter()
    {
        if (!isset($_SESSION["PRODUTO"])) return json_encode(null);

        if (!Banco::getInstance()->open()) return json_encode([]);
        $tipos = Produto::findById($_SESSION["PRODUTO"])->getTipos();
        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var TipoCaminhao $tipo */
        foreach ($tipos as $tipo) {
            $jarray[] = $tipo->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorChave(string $chave)
    {
        if (!isset($_SESSION["PRODUTO"])) return json_encode("Produto não selecionado.");

        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se com o banco de dados.");
        $tipos = Produto::findById($_SESSION["PRODUTO"])->getTipos();
        Banco::getInstance()->getConnection()->close();

        $tipos = array_filter($tipos, function (TipoCaminhao $tipo) use ($chave) {
            return stripos($tipo->getDescricao(), $chave) !== false;
        });

        $jarray = [];
        /** @var TipoCaminhao $tipo */
        foreach ($tipos as $tipo) {
            $jarray[] = $tipo->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function ordenar(string $col)
    {
        if (!isset($_SESSION["PRODUTO"])) return json_encode("Produto não selecionado.");

        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se com o banco de dados.");
        $tipos = Produto::findById($_SESSION["PRODUTO"])->getTipos();
        Banco::getInstance()->getConnection()->close();

        if (count($tipos) > 0) {
            switch ($col) {
                case "1":
                    usort($tipos, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() < $b->getId()) ? -1 : 1);
                    });
                    break;
                case "2":
                    usort($tipos, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() > $b->getId()) ? -1 : 1);
                    });
                    break;
                case "3":
                    usort($tipos, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) < 0) ? -1 : 1);
                    });
                    break;
                case "4":
                    usort($tipos, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) > 0) ? -1 : 1);
                    });
                    break;
                case "5":
                    usort($tipos, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if ($a->getEixos() === $b->getEixos()) return 0;
                        return (($a->getEixos() < $b->getEixos()) ? -1 : 1);
                    });
                    break;
                case "6":
                    usort($tipos, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if ($a->getEixos() === $b->getEixos()) return 0;
                        return (($a->getEixos() > $b->getEixos()) ? -1 : 1);
                    });
                    break;
                case "7":
                    usort($tipos, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if ($a->getCapacidade() === $b->getCapacidade()) return 0;
                        return (($a->getCapacidade() < $b->getCapacidade()) ? -1 : 1);
                    });
                    break;
                case "8":
                    usort($tipos, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if ($a->getCapacidade() === $b->getCapacidade()) return 0;
                        return (($a->getCapacidade() > $b->getCapacidade()) ? -1 : 1);
                    });
                    break;
            }
        }

        $jarray = [];
        /** @var TipoCaminhao $tipo */
        foreach ($tipos as $tipo) {
            $jarray[] = $tipo->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function verificarTipo(int $tipo)
    {
        if (!Banco::getInstance()->open()) return json_encode(false);
        $res = Produto::verifyType($_SESSION["PRODUTO"], $tipo);
        Banco::getInstance()->getConnection()->close();

        return json_encode($res);
    }

    public function excluir(int $id)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");

        $product = Produto::findById($_SESSION["PRODUTO"]);

        Banco::getInstance()->getConnection()->begin_transaction();

        $res = $product->deleteType($id);
        if ($res == -10 || $res -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um erro ao remover o vínculo.");
        }
        if ($res == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetro incorreto.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }

    public function adicionar(int $tipo)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");

        $product = Produto::findById($_SESSION["PRODUTO"]);

        Banco::getInstance()->getConnection()->begin_transaction();

        $res = $product->saveType($tipo);
        if ($res == -10 || $res -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um erro ao adicionar o vínculo.");
        }
        if ($res == -5) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetro incorreto.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}