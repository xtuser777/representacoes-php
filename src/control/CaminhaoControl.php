<?php


namespace scr\control;


use scr\model\Caminhao;
use scr\util\Banco;

class CaminhaoControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $caminhoes = Caminhao::findAll();
        Banco::getInstance()->getConnection()->close();
        $jarray = [];
        /** @var $caminhao Caminhao */
        foreach ($caminhoes as $caminhao) {
            $jarray[] = $caminhao->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorChave(string $chave)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $caminhoes = Caminhao::findByKey($chave);
        Banco::getInstance()->getConnection()->close();
        $jarray = [];
        /** @var $caminhao Caminhao */
        foreach ($caminhoes as $caminhao) {
            $jarray[] = $caminhao->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function ordenar(string $col)
    {
        if (!Banco::getInstance()->open()) return json_encode([]);
        $caminhoes = Caminhao::findAll();
        Banco::getInstance()->getConnection()->close();

        if (count($caminhoes) > 0) {
            switch ($col) {
                case "1":
                    usort($caminhoes, function (Caminhao $a, Caminhao $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() < $b->getId()) ? -1 : 1);
                    });
                    break;
                case "2":
                    usort($caminhoes, function (Caminhao $a, Caminhao $b) {
                        if ($a->getId() === $b->getId()) return 0;
                        return (($a->getId() > $b->getId()) ? -1 : 1);
                    });
                    break;
                case "3":
                    usort($caminhoes, function (Caminhao $a, Caminhao $b) {
                        if (strcasecmp($a->getPlaca(), $b->getPlaca()) === 0) return 0;
                        return ((strcasecmp($a->getPlaca(), $b->getPlaca()) < 0) ? -1 : 1);
                    });
                    break;
                case "4":
                    usort($caminhoes, function (Caminhao $a, Caminhao $b) {
                        if (strcasecmp($a->getPlaca(), $b->getPlaca()) === 0) return 0;
                        return ((strcasecmp($a->getPlaca(), $b->getPlaca()) > 0) ? -1 : 1);
                    });
                    break;
                case "5":
                    usort($caminhoes, function (Caminhao $a, Caminhao $b) {
                        if (strcasecmp($a->getMarca(), $b->getMarca()) === 0) return 0;
                        return ((strcasecmp($a->getMarca(), $b->getMarca()) < 0) ? -1 : 1);
                    });
                    break;
                case "6":
                    usort($caminhoes, function (Caminhao $a, Caminhao $b) {
                        if (strcasecmp($a->getMarca(), $b->getMarca()) === 0) return 0;
                        return ((strcasecmp($a->getMarca(), $b->getMarca()) > 0) ? -1 : 1);
                    });
                    break;
                case "7":
                    usort($caminhoes, function (Caminhao $a, Caminhao $b) {
                        if (strcasecmp($a->getModelo(), $b->getModelo()) === 0) return 0;
                        return ((strcasecmp($a->getModelo(), $b->getModelo()) < 0) ? -1 : 1);
                    });
                    break;
                case "8":
                    usort($caminhoes, function (Caminhao $a, Caminhao $b) {
                        if (strcasecmp($a->getModelo(), $b->getModelo()) === 0) return 0;
                        return ((strcasecmp($a->getModelo(), $b->getModelo()) > 0) ? -1 : 1);
                    });
                    break;
                case "9":
                    usort($caminhoes, function (Caminhao $a, Caminhao $b) {
                        if (strcasecmp($a->getAnoFabricacao(), $b->getAnoFabricacao()) === 0) return 0;
                        return ((strcasecmp($a->getAnoFabricacao(), $b->getAnoFabricacao()) < 0) ? -1 : 1);
                    });
                    break;
                case "10":
                    usort($caminhoes, function (Caminhao $a, Caminhao $b) {
                        if (strcasecmp($a->getAnoFabricacao(), $b->getAnoFabricacao()) === 0) return 0;
                        return ((strcasecmp($a->getAnoFabricacao(), $b->getAnoFabricacao()) > 0) ? -1 : 1);
                    });
                    break;
            }
        }

        $jarray = [];
        /** @var $caminhao Caminhao */
        foreach ($caminhoes as $caminhao) {
            $jarray[] = $caminhao->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function enviar(int $id)
    {
        if ($id <= 0) { return json_encode('Parâmetro inválido.'); }
        setcookie('CAMINHAO', $id, time() + (3600), "/", "", 0, 1);

        return json_encode('');
    }

    public function excluir(int $id)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se ao banco de dados.");
        $caminhao = Caminhao::findById($id);
        if (!$caminhao) return json_encode("Registro não encontrado.");
        $res = $caminhao->delete();
        if ($res == -10 || $res == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao excluir o caminhão.");
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