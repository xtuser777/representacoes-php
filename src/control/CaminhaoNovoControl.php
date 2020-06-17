<?php namespace scr\control;

use scr\model\Caminhao;
use scr\model\Motorista;
use scr\model\Proprietario;
use scr\model\TipoCaminhao;
use scr\util\Banco;

class CaminhaoNovoControl
{
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

    public function gravar(string $placa, string $marca, string $modelo, string $cor, string $anofab, string $anomod, int $tipo, int $prop)
    {
        if (!Banco::getInstance()->open()) return json_encode("Erro ao conectar-se com o banco de dados.");
        $tip = TipoCaminhao::findById($tipo);
        $prp = (new Proprietario())->findById($prop);
        Banco::getInstance()->getConnection()->begin_transaction();
        $caminhao = new Caminhao(0, $placa, $marca, $modelo, $cor, $anofab, $anomod, $tip, $prp);
        $res = $caminhao->save();
        if ($res == -10 || $res == -1 || $res == 0) {
            Banco::getInstance()->getConnection()->rollback();
            Banco::getInstance()->getConnection()->close();
            return json_encode("Ocorreu um problema ao salvar o caminhão.");
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