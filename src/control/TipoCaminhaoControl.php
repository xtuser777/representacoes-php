<?php namespace scr\control;

use scr\model\TipoCaminhao;
use scr\util\Banco;

class TipoCaminhaoControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $array = TipoCaminhao::findAll();
        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var TipoCaminhao $item */
        foreach ($array as $item) {
            $jarray[] = $item->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorDescricao(string $descricao)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $array = TipoCaminhao::findByDescription($descricao);
        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var TipoCaminhao $item */
        foreach ($array as $item) {
            $jarray[] = $item->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function ordenar(string $col)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $array = TipoCaminhao::findAll();
        Banco::getInstance()->getConnection()->close();

        if (count($array) > 0) {
            switch ($col) {
                case "1":
                    usort($array, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() < $b->getId()) ? -1 : 1);
                    });
                    break;
                case "2":
                    usort($array, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() > $b->getId()) ? -1 : 1);
                    });
                    break;
                case "3":
                    usort($array, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) < 0) ? -1 : 1);
                    });
                    break;
                case "4":
                    usort($array, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if (strcasecmp($a->getDescricao(), $b->getDescricao()) === 0) return 0;
                        return ((strcasecmp($a->getDescricao(), $b->getDescricao()) > 0) ? -1 : 1);
                    });
                    break;
                case "5":
                    usort($array, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if ($a->getEixos() === $b->getEixos()) return 0;
                        return (($a->getEixos() < $b->getEixos()) ? -1 : 1);
                    });
                    break;
                case "6":
                    usort($array, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if ($a->getEixos() === $b->getEixos()) return 0;
                        return (($a->getEixos() > $b->getEixos()) ? -1 : 1);
                    });
                    break;
                case "7":
                    usort($array, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if ($a->getCapacidade() === $b->getCapacidade()) return 0;
                        return (($a->getCapacidade() < $b->getCapacidade()) ? -1 : 1);
                    });
                    break;
                case "8":
                    usort($array, function (TipoCaminhao $a, TipoCaminhao $b) {
                        if ($a->getCapacidade() === $b->getCapacidade()) return 0;
                        return (($a->getCapacidade() > $b->getCapacidade()) ? -1 : 1);
                    });
                    break;
            }
        }

        $jarray = [];
        /** @var TipoCaminhao $item */
        foreach ($array as $item) {
            $jarray[] = $item->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function enviar(int $id)
    {
        if ($id <= 0) { return json_encode('Parâmetro inválido.'); }
        $_SESSION['TIPO'] = $id;

        return json_encode('');
    }

    public function excluir(int $id)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");

        $tipo = TipoCaminhao::findById($id);
        if (!$tipo) return json_encode("Registro não encontrado.");

        $dependents = $tipo->dependents();
        if ($dependents == -5) return json_encode("Objeto inválido.");
        if ($dependents > 0) return json_encode("Este registro possui dependentes, remova-os primeiro para deletar este.");

        Banco::getInstance()->getConnection()->begin_transaction();

        $tip = $tipo->delete();
        if ($tip == -10 || $tip == -1) {
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao deletar o tipo de caminhão.");
        }
        if ($tip == -5) {
            Banco::getInstance()->getConnection()->close();
            return json_encode("Parâmetro inválido.");
        }

        Banco::getInstance()->getConnection()->commit();
        Banco::getInstance()->getConnection()->close();

        return json_encode("");
    }
}