<?php


namespace scr\control;


use scr\model\Evento;
use scr\util\Banco;

class InicioControl
{
    public function obter()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $eventos = (new Evento())->findAll();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Evento $evento */
        foreach ($eventos as $evento) {
            $serial[] = $evento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroDataTipo(string $filtro, string $data, int $tipo)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $eventos = (new Evento())->findByFilterDateType($filtro, $data, $tipo);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Evento $evento */
        foreach ($eventos as $evento) {
            $serial[] = $evento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroTipo(string $filtro, int $tipo)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $eventos = (new Evento())->findByFilterType($filtro, $tipo);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Evento $evento */
        foreach ($eventos as $evento) {
            $serial[] = $evento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorDataTipo(string $data, int $tipo)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $eventos = (new Evento())->findByDateType($data, $tipo);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Evento $evento */
        foreach ($eventos as $evento) {
            $serial[] = $evento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorTipo(int $tipo)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $eventos = (new Evento())->findByType($tipo);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Evento $evento */
        foreach ($eventos as $evento) {
            $serial[] = $evento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroData(string $filtro, string $data)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $eventos = (new Evento())->findByFilterDate($filtro, $data);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Evento $evento */
        foreach ($eventos as $evento) {
            $serial[] = $evento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltro(string $filtro)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $eventos = (new Evento())->findByFilter($filtro);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Evento $evento */
        foreach ($eventos as $evento) {
            $serial[] = $evento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorData(string $data)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $eventos = (new Evento())->findByDate($data);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Evento $evento */
        foreach ($eventos as $evento) {
            $serial[] = $evento->jsonSerialize();
        }

        return json_encode($serial);
    }
}