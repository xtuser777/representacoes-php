<?php


namespace scr\control;


use scr\model\ContaPagar;
use scr\model\Funcionario;
use scr\model\Parametrizacao;
use scr\util\Banco;
use scr\util\Retatorio;

class RelatorioContasPagarControl
{
    public function obter(int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaPagar())->findAll($this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltros(string $filtro, string $inicio, string $fim, string $venc, int $comissao, int $vendedor, int $situacao, int $ordem)
    {
        if (!Banco::getInstance()->open()) 
            return json_encode([]);
        
        $contas = (new ContaPagar())->findByFiltersReport($filtro, $inicio, $fim, $venc, $comissao, $vendedor, $situacao, $this->traduzirOrdem($ordem));
        
        Banco::getInstance()->getConnection()->close();
        
        $serial = [];
        /** @var $conta ContaPagar */
        foreach ($contas as $conta) {
            $serial[] = $conta->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterVendedores()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $vendedores = Funcionario::getVendedores();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $vendedor Funcionario */
        foreach ($vendedores as $vendedor) {
            $serial[] = $vendedor->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function traduzirOrdem(int $ordem)
    {
        $ordemTraduzida = "";

        switch ($ordem) {
            case "1": $ordemTraduzida = "con_pag_conta,con_pag_parcela"; break;

            case "2": $ordemTraduzida = "con_pag_conta"; break;

            case "3": $ordemTraduzida = "con_pag_conta DESC"; break;

            case "4": $ordemTraduzida = "con_pag_descricao"; break;

            case "5": $ordemTraduzida = "con_pag_descricao DESC"; break;

            case "6": $ordemTraduzida = "con_pag_parcela"; break;

            case "7": $ordemTraduzida = "con_pag_parcela DESC"; break;

            case "8": $ordemTraduzida = "con_pag_valor"; break;

            case "9": $ordemTraduzida = "con_pag_valor DESC"; break;

            case "10": $ordemTraduzida = "con_pag_vencimento"; break;

            case "11": $ordemTraduzida = "con_pag_vencimento DESC"; break;

            case "12": $ordemTraduzida = "con_pag_situacao"; break;

            case "13": $ordemTraduzida = "con_pag_situacao DESC"; break;
        }

        return $ordemTraduzida;
    }

    public function gerarRelatorioContas(string $filtro, string $inicio, string $fim, string $venc, int $comissao, int $vendedor, int $situacao, int $ordem): string
    {
        if (!Banco::getInstance()->open())
            return "Erro ao conectar no banco de dados.";

        $parametrizacao = Parametrizacao::get();

        $contas = [];

        $tituto = "RELATÓRIO DE CONTAS A PAGAR";
        $filtros = "";

        if ($filtro === '' && $inicio === '' && $fim === '' && $venc === '' && $comissao === 0 && $vendedor === 0 && $situacao === 0) {
            $contas = (new ContaPagar())->findAll($this->traduzirOrdem($ordem));
            $filtros = "SEM FILTRAGEM";
        } else {
            if ($filtro !== '' || $inicio !== '' || $fim !== '' || $venc !== '' || $comissao > 0 || $vendedor > 0 || $situacao !== 0) {
                $contas = (new ContaPagar())->findByFiltersReport($filtro, $inicio, $fim, $venc, $comissao, $vendedor, $situacao, $this->traduzirOrdem($ordem));
                $filtros = "FILTRADO POR";

                if ($filtro !== "") {
                    $filtros .= " FILTRO ($filtro)";
                }

                if ($inicio !== "" && $fim !== "") {
                    if (strlen($filtros) > 12)
                        $filtros .= ",";
                    $filtros .= " PERÍODO ($inicio) - ($fim)";
                }

                if ($venc !== "") {
                    if (strlen($filtros) > 12)
                        $filtros .= ",";
                    $filtros .= " VENCIMENTO ($venc)";
                }

                if ($comissao > 0) {
                    if (strlen($filtros) > 12)
                        $filtros .= ",";
                    $com = "";
                    switch ($comissao) {case 1: $com = "SIM"; break; case 2: $com = "NÂO"; break;}
                    $filtros .= " COMISSÃO ($com)";
                }

                if ($vendedor > 0) {
                    if (strlen($filtros) > 12)
                        $filtros .= ",";
                    $ven = $contas[0]->getVendedor()->getPessoa()->getNome();
                    $filtros .= " VENDEDOR ($ven)";
                }

                if ($situacao > 0) {
                    if (strlen($filtros) > 12)
                        $filtros .= ",";
                    $sit = "";
                    switch ($situacao) { case 1: $sit = "PENDENTE"; break; case 2: $sit = "PAGO PARC."; break; case 3: $sit = "PAGO"; break; }
                    $filtros .= " SITUAÇÃO ($sit)";
                }
            } else {
                if (($inicio !== '' && $fim === '') || ($inicio === '' && $fim !== '')) {
                    exit("As datas de inicio e fim devem estar preenchidas.");
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
        $col3 = utf8_decode("PARCELA");
        $col4 = utf8_decode("VALOR (R$)");
        $col5 = utf8_decode("LANÇAMENTO");
        $col6 = utf8_decode("VENCIMENTO");
        $col7 = utf8_decode("PAGO (R$)");
        $col8 = utf8_decode("PAGAMENTO");
        $col9 = utf8_decode("SITUAÇÃO");

        $relatorio->SetFont("Arial", "B", 8);
        $relatorio->SetXY(10, 40);
        $relatorio->Cell(14, 4, $col1, "B");
        $relatorio->Cell(80,4, $col2, "B");
        $relatorio->Cell(18, 4, $col3, "B");
        $relatorio->Cell(25, 4, $col4, "B");
        $relatorio->Cell(30, 4, $col5, "B");
        $relatorio->Cell(30, 4, $col6, "B");
        $relatorio->Cell(25, 4, $col7, "B");
        $relatorio->Cell(30, 4, $col8, "B");
        $relatorio->Cell(25, 4, $col9, "B");

        $y = 46;
        $relatorio->SetFont("Arial", "", 8);
        /** @var ContaPagar $conta */
        foreach ($contas as $conta) {
            $con = $conta->getConta();
            $des = $conta->getDescricao();
            $par = $conta->getParcela();
            $vlr = $conta->getValor();
            $dat = $conta->getData();
            $ven = $conta->getVencimento();
            $pag = $conta->getValorPago();
            $pto = $conta->getDataPagamento();
            $sit = "";
            switch ($conta->getSituacao()) {
                case 1: $sit = "PENDENTE"; break;
                case 2: $sit = "PAGO PARC."; break;
                case 3: $sit = "PAGO"; break;
            }

            $col1 = utf8_decode("$con");
            $col2 = utf8_decode("$des");
            $col3 = utf8_decode("$par");
            $col4 = utf8_decode("$vlr");
            $col5 = utf8_decode("$dat");
            $col6 = utf8_decode("$ven");
            $col7 = utf8_decode("$pag");
            $col8 = utf8_decode("$pto");
            $col9 = utf8_decode("$sit");

            $relatorio->SetXY(10, $y);
            $relatorio->Cell(14, 4, $col1);
            $relatorio->Cell(80,4, $col2);
            $relatorio->Cell(18, 4, $col3);
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