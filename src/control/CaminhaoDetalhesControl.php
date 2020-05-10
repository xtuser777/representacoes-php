<?php namespace scr\control;

use scr\model\Caminhao;
use scr\model\Motorista;
use scr\model\TipoCaminhao;
use scr\util\Banco;

class CaminhaoDetalhesControl
{
    public function obter()
    {
        if (!isset($_SESSION["CAMINHAO"])) return json_encode(null);
        if (!Banco::getInstance()->open()) return json_encode(null);
        $caminhao = Caminhao::findById($_SESSION["CAMINHAO"]);
        Banco::getInstance()->getConnection()->close();

        return json_encode($caminhao != null ? $caminhao->jsonSerialize() : null);
    }

    public function alterar(int $cam, string $placa, string $marca, string $modelo, string $anofab, string $anomod, int $tipo, int $prop)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se com o banco de dados.");
        $tip = TipoCaminhao::findById($tipo);
        $mot = Motorista::findById($prop);
        Banco::getInstance()->getConnection()->begin_transaction();
        $caminhao = new Caminhao($cam, $placa, $marca, $modelo, $anofab, $anomod, $tip, $mot);
        $res = $caminhao->update();
        if ($res == -10 || $res == -1) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao alterar o caminhão.");
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