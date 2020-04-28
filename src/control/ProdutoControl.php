<?php namespace scr\control;

use scr\model\Produto;
use scr\model\Representacao;
use scr\util\Banco;

class ProdutoControl
{
    public function obterRepresentacoes()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $representacoes = Representacao::getAll();
        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var Representacao $representacao */
        foreach ($representacoes as $representacao) {
            $jarray[] = $representacao->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obter()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $produtos = Produto::findAll();
        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var Produto $produto */
        foreach ($produtos as $produto) {
            $jarray[] = $produto->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorChaveRepresentacao(string $key, int $representacao)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $produtos = Produto::findByKeyRepresentation($key, $representacao);
        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var Produto $produto */
        foreach ($produtos as $produto) {
            $jarray[] = $produto->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorChave(string $key)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $produtos = Produto::findByKey($key);
        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var Produto $produto */
        foreach ($produtos as $produto) {
            $jarray[] = $produto->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorRepresentacao(int $representacao)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $produtos = Produto::findByRepresentation($representacao);
        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var Produto $produto */
        foreach ($produtos as $produto) {
            $jarray[] = $produto->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function ordenar(string $col)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $produtos = Produto::findAll();
        Banco::getInstance()->getConnection()->close();

        if (count($produtos) > 0) {
            switch ($col) {
                case "1":
                    usort($produtos, function (Produto $a, Produto $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() < $b->getId()) ? -1 : 1);
                    });
                    break;
                case "2":
                    usort($produtos, function (Produto $a, Produto $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() > $b->getId()) ? -1 : 1);
                    });
                    break;
                case "3":
                    usort($produtos, function (Produto $a, Produto $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) < 0) ? -1 : 1);
                    });
                    break;
                case "4":
                    usort($produtos, function (Produto $a, Produto $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) > 0) ? -1 : 1);
                    });
                    break;
                case "5":
                    usort($produtos, function (Produto $a, Produto $b) {
                        if (strcasecmp($a->getMedida(), $b->getMedida()) === 0) return 0;
                        return ((strcasecmp($a->getMedida(), $b->getMedida()) < 0) ? -1 : 1);
                    });
                    break;
                case "6":
                    usort($produtos, function (Produto $a, Produto $b) {
                        if (strcasecmp($a->getMedida(), $b->getMedida()) === 0) return 0;
                        return ((strcasecmp($a->getMedida(), $b->getMedida()) > 0) ? -1 : 1);
                    });
                    break;
                case "7":
                    usort($produtos, function (Produto $a, Produto $b) {
                        if ($a->getPreco() === $b->getPreco()) return 0;
                        return (($a->getPreco() < $b->getPreco()) ? -1 : 1);
                    });
                    break;
                case "8":
                    usort($produtos, function (Produto $a, Produto $b) {
                        if ($a->getPreco() === $b->getPreco()) return 0;
                        return (($a->getPreco() > $b->getPreco()) ? -1 : 1);
                    });
                    break;
                case "9":
                    usort($produtos, function (Produto $a, Produto $b) {
                        if (strcasecmp($a->getRepresentacao()->getPessoa()->getNomeFantasia(), $b->getRepresentacao()->getPessoa()->getNomeFantasia()) === 0) return 0;
                        return ((strcasecmp($a->getRepresentacao()->getPessoa()->getNomeFantasia(), $b->getRepresentacao()->getPessoa()->getNomeFantasia()) < 0) ? -1 : 1);
                    });
                    break;
                case "10":
                    usort($produtos, function (Produto $a, Produto $b) {
                        if (strcasecmp($a->getRepresentacao()->getPessoa()->getNomeFantasia(), $b->getRepresentacao()->getPessoa()->getNomeFantasia()) === 0) return 0;
                        return ((strcasecmp($a->getRepresentacao()->getPessoa()->getNomeFantasia(), $b->getRepresentacao()->getPessoa()->getNomeFantasia()) > 0) ? -1 : 1);
                    });
                    break;
            }
        }

        $jarray = [];
        /** @var Produto $produto */
        foreach ($produtos as $produto) {
            $jarray[] = $produto->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function enviar(int $id)
    {
        if ($id <= 0) { return json_encode('Parâmetro inválido.'); }
        $_SESSION['PRODUTO'] = $id;

        return json_encode('');
    }

    public function excluir(int $id)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");

        $produto = Produto::findById($id);
        if (!$produto) return json_encode("Registro não encontrado.");

        Banco::getInstance()->getConnection()->begin_transaction();

        $res = $produto->delete();
        if ($res == -10 || $res == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um erro ao deletar o produto.");
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