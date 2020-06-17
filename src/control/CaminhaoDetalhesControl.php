<?php namespace scr\control;

use scr\model\Caminhao;
use scr\model\Motorista;
use scr\model\Proprietario;
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

    public function obterTipos()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);
        $tipos = TipoCaminhao::findAll();
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var TipoCaminhao $tipo */
        foreach ($tipos as $tipo) {
            $serial[] = $tipo->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterProprietarios()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);
        $props = (new Proprietario())->findAll();
        Banco::getInstance()->getConnection()->close();
        $serial = [];
        /** @var Proprietario $prop */
        foreach ($props as $prop) {
            $serial[] = $prop->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function alterar(int $cam, string $placa, string $marca, string $modelo, string $cor, string $anofab, string $anomod, int $tipo, int $prop)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se com o banco de dados.");
        $tip = TipoCaminhao::findById($tipo);
        $prp = (new Proprietario())->findById($prop);
        Banco::getInstance()->getConnection()->begin_transaction();
        $caminhao = new Caminhao($cam, $placa, $marca, $modelo, $cor, $anofab, $anomod, $tip, $prp);
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