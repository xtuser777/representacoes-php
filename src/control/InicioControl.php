<?php


namespace scr\control;


use scr\model\Evento;
use scr\model\Parametrizacao;
use scr\util\Banco;
use scr\util\Retatorio;

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

    public function gerarRelatorioEventos(string $filtro, string $data, int $tipo): string
    {
        if (!Banco::getInstance()->open())
            return "Erro ao conectar no banco de dados.";

        $parametrizacao = Parametrizacao::get();

        $eventos = [];

        $tituto = "RELATÓRIO DE EVENTOS DO SISTEMA";
        $filtros = "";

        if ($filtro === '' && $data === '' && $tipo === 0) {
            $eventos = (new Evento())->findAll();
            $filtros = "SEM FILTRAGEM";
        } else {
            if ($filtro !== '' && $data !== '' && $tipo > 0) {
                $eventos = (new Evento())->findByFilterDateType($filtro, $data, $tipo);
                $tip = $tipo === 1 ? "VENDA" : "FRETE";
                $filtros = "FILTRADO POR FILTRO ($filtro), DATA ($data) E TIPO ($tip)";
            } else {
                if ($filtro !== '' && $data === '' && $tipo > 0) {
                    $eventos = (new Evento())->findByFilterType($filtro, $tipo);
                    $tip = $tipo === 1 ? "VENDA" : "FRETE";
                    $filtros = "FILTRADO POR FILTRO ($filtro) E TIPO ($tip)";
                } else {
                    if ($filtro === '' && $data !== '' && $tipo > 0) {
                        $eventos = (new Evento())->findByDateType($data, $tipo);
                        $tip = $tipo === 1 ? "VENDA" : "FRETE";
                        $filtros = "FILTRADO POR DATA ($data) E TIPO ($tip)";
                    } else {
                        if ($filtro === '' && $data === '' && $tipo > 0) {
                            $eventos = (new Evento())->findByType($tipo);
                            $tip = $tipo === 1 ? "VENDA" : "FRETE";
                            $filtros = "FILTRADO POR TIPO ($tip)";
                        } else {
                            if ($filtro !== '' && $data !== '' && $tipo === 0) {
                                $eventos = (new Evento())->findByFilterDate($filtro, $data);
                                $filtros = "FILTRADO POR FILTRO ($filtro) E DATA ($data)";
                            } else {
                                if ($filtro !== '' && $data === '' && $tipo === 0) {
                                    $eventos = (new Evento())->findByFilter($filtro);
                                    $filtros = "FILTRADO POR FILTRO ($filtro)";
                                } else {
                                    if ($filtro === '' && $data !== '' && $tipo === 0) {
                                        $eventos = (new Evento())->findByDate($data);
                                        $filtros = "FILTRADO POR DATA ($data)";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        Banco::getInstance()->getConnection()->close();

        $par = (object) $parametrizacao->jsonSerialize();
        $par->pessoa = (object) $par->pessoa;
        $par->pessoa->contato = (object) $par->pessoa->contato;
        $par->pessoa->contato->endereco = (object) $par->pessoa->contato->endereco;
        $par->pessoa->contato->endereco->cidade = (object) $par->pessoa->contato->endereco->cidade;
        $par->pessoa->contato->endereco->cidade->estado = (object) $par->pessoa->contato->endereco->cidade->estado;

        $relatorio = new Retatorio("L", "mm", "A4", $par);
        $relatorio->AddPage();
        $relatorio->TituloRelatorio($tituto, $filtros);

        $col1 = utf8_decode("CÓD.");
        $col2 = utf8_decode("DESCRIÇÃO");
        $col3 = utf8_decode("DATA");
        $col4 = utf8_decode("HORA");
        $col5 = utf8_decode("PEDIDO");
        $col6 = utf8_decode("AUTOR");

        $relatorio->SetFont("Arial", "B", 9);
        $relatorio->SetXY(10, 40);
        $relatorio->Cell(12, 4, $col1, "B");
        $relatorio->Cell(116,4, $col2, "B");
        $relatorio->Cell(19, 4, $col3, "B");
        $relatorio->Cell(15, 4, $col4, "B");
        $relatorio->Cell(58, 4, $col5, "B");
        $relatorio->Cell(56, 4, $col6, "B");

        $y = 46;
        $relatorio->SetFont("Arial", "", 9);
        /** @var Evento $evento */
        foreach ($eventos as $evento) {
            $cod = $evento->getId();
            $des = $evento->getDescricao();
            $dat = $evento->getData();
            $hor = $evento->getHora();

            $ped = "";
            if ($evento->getPedidoVenda()) {
                $ped = $evento->getPedidoVenda()->getDescricao();
            } elseif ($evento->getPedidoFrete()) {
                $ped = $evento->getPedidoFrete()->getDescricao();
            }

            $atr = $evento->getAutor()->getFuncionario()->getPessoa()->getNome();

            $col1 = utf8_decode("$cod");
            $col2 = utf8_decode("$des");
            $col3 = utf8_decode("$dat");
            $col4 = utf8_decode("$hor");
            $col5 = utf8_decode("$ped");
            $col6 = utf8_decode("$atr");

            $relatorio->SetXY(10, $y);
            $relatorio->Cell(12, 4, $col1);
            $relatorio->Cell(116, 4, $col2);
            $relatorio->Cell(19, 4, $col3);
            $relatorio->Cell(15, 4, $col4);
            $relatorio->Cell(58, 4, $col5);
            $relatorio->Cell(56, 4, $col6);

            $y += 6;
        }

        $data = date("dmY");
        $hora = date("His");

        return $relatorio->Output("I", "RelatorioEventos-$data-$hora");
    }
}
