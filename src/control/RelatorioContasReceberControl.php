<?php


namespace scr\control;


use scr\model\ContaReceber;
use scr\model\Parametrizacao;
use scr\util\Banco;
use scr\util\Retatorio;

class RelatorioContasReceberControl
{
    public function obter(int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaReceber())->findAll($this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorVencimento(string $venc, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByVenc($venc, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltro(string $filtro, int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaReceber())->findByFilter($filtro, $this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroVencimento(string $filtro, string $venc, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByFilterVenc($filtro, $venc, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroSituacao(string $filtro, int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByFilterSituation($filtro, $situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroVencimentoSituacao(string $filtro, string $venc, int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByFilterVencSituation($filtro, $venc, $situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorSituacao(int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findBySituation($situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorVencimentoSituacao(string $venc, int $situation, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByVencSituation($venc, $situation, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodo(string $data1, string $data2, int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaReceber())->findByPeriod2($data1, $data2, $this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoVencimento(string $data1, string $data2, string $venc, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByPeriodVenc($data1, $data2, $venc, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoSituacao(string $data1, string $data2, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByPeriod2Situation($data1, $data2, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoVencimentoSituacao(string $data1, string $data2, string $venc, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByPeriodVencSituation($data1, $data2, $venc, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodo(string $filtro, string $data1, string $data2, int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaReceber())->findByFilterPeriod($filtro, $data1, $data2, $this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoVencimento(string $filtro, string $data1, string $data2, string $venc, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByFilterPeriodVenc($filtro, $data1, $data2, $venc, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoSituacao(string $filtro, string $data1, string $data2, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByFilterPeriodSituation($filtro, $data1, $data2, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoVencimentoSituacao(string $filtro, string $data1, string $data2, string $venc, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $contas = (new ContaReceber())->findByFilterPeriodVencSituation($filtro, $data1, $data2, $venc, $situacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $conta ContaReceber */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function traduzirOrdem(int $ordem)
    {
        $ordemTraduzida = "";

        switch ($ordem) {
            case "1": $ordemTraduzida = "con_rec_conta"; break;

            case "2": $ordemTraduzida = "con_rec_conta DESC"; break;

            case "3": $ordemTraduzida = "con_rec_descricao"; break;

            case "4": $ordemTraduzida = "con_rec_descricao DESC"; break;

            case "5": $ordemTraduzida = "con_rec_valor"; break;

            case "6": $ordemTraduzida = "con_rec_valor DESC"; break;

            case "7": $ordemTraduzida = "con_rec_vencimento"; break;

            case "8": $ordemTraduzida = "con_rec_vencimento DESC"; break;

            case "9": $ordemTraduzida = "con_rec_situacao"; break;

            case "10": $ordemTraduzida = "con_rec_situacao DESC"; break;
        }

        return $ordemTraduzida;
    }

    public function gerarRelatorioContas(string $filtro, string $inicio, string $fim, string $venc, int $situacao, int $ordem): string
    {
        if (!Banco::getInstance()->open())
            return "Erro ao conectar no banco de dados.";

        $parametrizacao = Parametrizacao::get();

        $contas = [];

        $tituto = "RELATÓRIO DE CONTAS A RECEBER";
        $filtros = "";

        if ($filtro === '' && $inicio === '' && $fim === '' && $venc === '' && $situacao === 0) {
            $contas = (new ContaReceber())->findAll($this->traduzirOrdem($ordem));
            $filtros = "SEM FILTRAGEM";
        } else {
            if ($filtro !== '' && $inicio !== '' && $fim !== '' && $venc !== '' && $situacao !== 0) {
                $contas = (new ContaReceber())->findByFilterPeriodVencSituation($filtro, $inicio, $fim, $venc, $situacao, $this->traduzirOrdem($ordem));
                $sit = "";
                switch ($situacao) { case 1: $sit = "PENDENTE"; break; case 2: $sit = "PAGO PARC."; break; case 3: $sit = "PAGO"; break; }
                $filtros = "FILTRADO POR FILTRO ($filtro), PERÍODO ($inicio) - ($fim), VENCIMENTO ($venc), SITUAÇÃO ($sit)";
            } else {
                if ($filtro !== '' && $inicio !== '' && $fim !== '' && $venc !== '' && $situacao === 0) {
                    $contas = (new ContaReceber())->findByFilterPeriodVenc($filtro, $inicio, $fim, $venc, $this->traduzirOrdem($ordem));
                    $filtros = "FILTRADO POR FILTRO ($filtro), PERÍODO ($inicio) - ($fim), VENCIMENTO ($venc)";
                } else {
                    if ($filtro !== '' && $inicio !== '' && $fim !== '' && $venc === '' && $situacao !== 0) {
                        $contas = (new ContaReceber())->findByFilterPeriodSituation($filtro, $inicio, $fim, $situacao, $this->traduzirOrdem($ordem));
                        $sit = "";
                        switch ($situacao) { case 1: $sit = "PENDENTE"; break; case 2: $sit = "PAGO PARC."; break; case 3: $sit = "PAGO"; break; }
                        $filtros = "FILTRADO POR FILTRO ($filtro), PERÍODO ($inicio) - ($fim), SITUAÇÃO ($sit)";
                    } else {
                        if ($filtro !== '' && $inicio !== '' && $fim !== '' && $venc === '' && $situacao === 0) {
                            $contas = (new ContaReceber())->findByFilterPeriod($filtro, $inicio, $fim, $this->traduzirOrdem($ordem));
                            $filtros = "FILTRADO POR FILTRO ($filtro), PERÍODO ($inicio) - ($fim)";
                        } else {
                            if ($filtro !== '' && $inicio === '' && $fim === '' && $venc !== '' && $situacao !== 0) {
                                $contas = (new ContaReceber())->findByFilterVencSituation($filtro, $venc, $situacao, $this->traduzirOrdem($ordem));
                                $sit = "";
                                switch ($situacao) { case 1: $sit = "PENDENTE"; break; case 2: $sit = "PAGO PARC."; break; case 3: $sit = "PAGO"; break; }
                                $filtros = "FILTRADO POR VENCIMENTO ($venc), SITUAÇÃO ($sit)";
                            } else {
                                if ($filtro !== '' && $inicio === '' && $fim === '' && $venc !== '' && $situacao === 0) {
                                    $contas = (new ContaReceber())->findByFilterVenc($filtro, $venc, $this->traduzirOrdem($ordem));
                                    $filtros = "FILTRADO POR FILTRO ($filtro), VENCIMENTO ($venc)";
                                } else {
                                    if ($filtro !== '' && $inicio === '' && $fim === '' && $venc === '' && $situacao !== 0) {
                                        $contas = (new ContaReceber())->findByFilterSituation($filtro, $situacao, $this->traduzirOrdem($ordem));
                                        $sit = "";
                                        switch ($situacao) { case 1: $sit = "PENDENTE"; break; case 2: $sit = "PAGO PARC."; break; case 3: $sit = "PAGO"; break; }
                                        $filtros = "FILTRADO POR FILTRO ($filtro), SITUAÇÃO ($sit)";
                                    } else {
                                        if ($filtro !== '' && $inicio === '' && $fim === '' && $venc === '' && $situacao === 0) {
                                            $contas = (new ContaReceber())->findByFilter($filtro, $this->traduzirOrdem($ordem));
                                            $filtros = "FILTRADO POR FILTRO ($filtro)";
                                        } else {
                                            if ($filtro === '' && $inicio !== '' && $fim !== '' && $venc !== '' && $situacao !== 0) {
                                                $contas = (new ContaReceber())->findByFilterPeriodVencSituation($filtro, $inicio, $fim, $venc, $situacao, $this->traduzirOrdem($ordem));
                                                $sit = "";
                                                switch ($situacao) { case 1: $sit = "PENDENTE"; break; case 2: $sit = "PAGO PARC."; break; case 3: $sit = "PAGO"; break; }
                                                $filtros = "FILTRADO POR PERÍODO ($inicio) - ($fim), VENCIMENTO ($venc), SITUAÇÃO ($sit)";
                                            } else {
                                                if ($filtro === '' && $inicio !== '' && $fim !== '' && $venc !== '' && $situacao === 0) {
                                                    $contas = (new ContaReceber())->findByPeriodVenc($inicio, $fim, $venc, $this->traduzirOrdem($ordem));
                                                    $filtros = "FILTRADO POR PERÍODO ($inicio) - ($fim), VENCIMENTO ($venc)";
                                                } else {
                                                    if ($filtro === '' && $inicio !== '' && $fim !== '' && $venc === '' && $situacao !== 0) {
                                                        $contas = (new ContaReceber())->findByPeriod2Situation($inicio, $fim, $situacao, $this->traduzirOrdem($ordem));
                                                        $sit = "";
                                                        switch ($situacao) { case 1: $sit = "PENDENTE"; break; case 2: $sit = "PAGO PARC."; break; case 3: $sit = "PAGO"; break; }
                                                        $filtros = "FILTRADO POR PERÍODO ($inicio) - ($fim), SITUAÇÃO ($sit)";
                                                    } else {
                                                        if ($filtro === '' && $inicio !== '' && $fim !== '' && $venc === '' && $situacao === 0) {
                                                            $contas = (new ContaReceber())->findByPeriod2($inicio, $fim, $this->traduzirOrdem($ordem));
                                                            $filtros = "FILTRADO POR PERÍODO ($inicio) - ($fim)";
                                                        } else {
                                                            if ($filtro === '' && $inicio === '' && $fim === '' && $venc !== '' && $situacao !== 0) {
                                                                $contas = (new ContaReceber())->findByVencSituation($venc, $situacao, $this->traduzirOrdem($ordem));
                                                                $sit = "";
                                                                switch ($situacao) { case 1: $sit = "PENDENTE"; break; case 2: $sit = "PAGO PARC."; break; case 3: $sit = "PAGO"; break; }
                                                                $filtros = "FILTRADO POR VENCIMENTO ($venc), SITUAÇÃO ($sit)";
                                                            } else {
                                                                if ($filtro === '' && $inicio === '' && $fim === '' && $venc !== '' && $situacao === 0) {
                                                                    $contas = (new ContaReceber())->findByVenc($venc, $this->traduzirOrdem($ordem));
                                                                    $filtros = "FILTRADO POR VENCIMENTO ($venc)";
                                                                } else {
                                                                    if ($filtro === '' && $inicio === '' && $fim === '' && $venc === '' && $situacao !== 0) {
                                                                        $contas = (new ContaReceber())->findBySituation($situacao, $this->traduzirOrdem($ordem));
                                                                        $sit = "";
                                                                        switch ($situacao) { case 1: $sit = "PENDENTE"; break; case 2: $sit = "PAGO PARC."; break; case 3: $sit = "PAGO"; break; }
                                                                        $filtros = "FILTRADO POR SITUAÇÃO ($sit)";
                                                                    } else {
                                                                        if (($inicio !== '' && $fim === '') || ($inicio === '' && $fim !== '')) {
                                                                            exit("As datas de inicio e fim devem estar preenchidas.");
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
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

        $col1 = utf8_decode("CONTA");
        $col2 = utf8_decode("DESCRIÇÃO");
        $col4 = utf8_decode("VALOR (R$)");
        $col5 = utf8_decode("LANÇAMENTO");
        $col6 = utf8_decode("VENCIMENTO");
        $col7 = utf8_decode("RECEBIDO (R$)");
        $col8 = utf8_decode("RECEBIMENTO");
        $col9 = utf8_decode("SITUAÇÃO");

        $relatorio->SetFont("Arial", "B", 8);
        $relatorio->SetXY(10, 40);
        $relatorio->Cell(14, 4, $col1, "B");
        $relatorio->Cell(80,4, $col2, "B");
        $relatorio->Cell(25, 4, $col4, "B");
        $relatorio->Cell(30, 4, $col5, "B");
        $relatorio->Cell(30, 4, $col6, "B");
        $relatorio->Cell(25, 4, $col7, "B");
        $relatorio->Cell(30, 4, $col8, "B");
        $relatorio->Cell(25, 4, $col9, "B");

        $y = 46;
        $relatorio->SetFont("Arial", "", 8);
        /** @var ContaReceber $conta */
        foreach ($contas as $conta) {
            $con = $conta->getConta();
            $des = $conta->getDescricao();
            $vlr = $conta->getValor();
            $dat = $conta->getData();
            $ven = $conta->getVencimento();
            $pag = $conta->getValorRecebido();
            $pto = $conta->getDataRecebimento();
            $sit = "";
            switch ($conta->getSituacao()) {
                case 1: $sit = "PENDENTE"; break;
                case 2: $sit = "PAGO PARC."; break;
                case 3: $sit = "PAGO"; break;
            }

            $col1 = utf8_decode("$con");
            $col2 = utf8_decode("$des");
            $col4 = utf8_decode("$vlr");
            $col5 = utf8_decode("$dat");
            $col6 = utf8_decode("$ven");
            $col7 = utf8_decode("$pag");
            $col8 = utf8_decode("$pto");
            $col9 = utf8_decode("$sit");

            $relatorio->SetXY(10, $y);
            $relatorio->Cell(14, 4, $col1);
            $relatorio->Cell(80,4, $col2);
            $relatorio->Cell(25, 4, $col4);
            $relatorio->Cell(30, 4, $col5);
            $relatorio->Cell(30, 4, $col6);
            $relatorio->Cell(25, 4, $col7);
            $relatorio->Cell(30, 4, $col8);
            $relatorio->Cell(25, 4, $col9);

            $y += 6;
        }

        $data = date("dmY");
        $hora = date("His");

        return $relatorio->Output("I", "RelatorioContasPagar-$data-$hora");
    }
}