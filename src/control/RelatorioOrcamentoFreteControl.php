<?php


namespace scr\control;


use scr\model\Cliente;
use scr\model\Parametrizacao;
use scr\model\OrcamentoFrete;
use scr\util\Banco;
use scr\util\Retatorio;

class RelatorioOrcamentoFreteControl
{
    public function obter(int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $orcamentos = (new OrcamentoFrete())->findAll($this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $orcamento OrcamentoFrete */
        foreach ($orcamentos as $orcamento) {
            $serial[] = $orcamento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorCliente(int $cliente, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $orcamentos = (new OrcamentoFrete)->findByClient($cliente, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $orcamento OrcamentoFrete */
        foreach ($orcamentos as $orcamento) {
            $serial[] = $orcamento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltro(string $filtro, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $orcamentos = (new OrcamentoFrete)->findByFilter($filtro, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $orcamento OrcamentoFrete */
        foreach ($orcamentos as $orcamento) {
            $serial[] = $orcamento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroCliente(string $filtro, int $cliente, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $orcamentos = (new OrcamentoFrete)->findByFilterClient($filtro, $cliente, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $orcamento OrcamentoFrete */
        foreach ($orcamentos as $orcamento) {
            $serial[] = $orcamento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodo(string $dataInicio, string $dataFim, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $orcamentos = (new OrcamentoFrete)->findByPeriod($dataInicio, $dataFim, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $orcamento OrcamentoFrete */
        foreach ($orcamentos as $orcamento) {
            $serial[] = $orcamento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoCliente(string $dataInicio, string $dataFim, int $cliente, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $orcamentos = (new OrcamentoFrete)->findByPeriodClient($dataInicio, $dataFim, $cliente, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $orcamento OrcamentoFrete */
        foreach ($orcamentos as $orcamento) {
            $serial[] = $orcamento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodo(string $filtro, string $dataInicio, string $dataFim, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $orcamentos = (new OrcamentoFrete)->findByFilterPeriod($filtro, $dataInicio, $dataFim, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $orcamento OrcamentoFrete */
        foreach ($orcamentos as $orcamento) {
            $serial[] = $orcamento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoCliente(string $filtro, string $dataInicio, string $dataFim, int $cliente, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $orcamentos = (new OrcamentoFrete)->findByFilterPeriodClient($filtro, $dataInicio, $dataFim, $cliente, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $orcamento OrcamentoFrete */
        foreach ($orcamentos as $orcamento) {
            $serial[] = $orcamento->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function traduzirOrdem(int $ordem)
    {
        $ordemTraduzida = "";

        switch ($ordem) {
            case 1: $ordemTraduzida = "o.orc_fre_descricao"; break;
            case 2: $ordemTraduzida = "o.orc_fre_descricao DESC"; break;
            case 3: $ordemTraduzida = "pf.pf_nome, pj.pj_nome_fantasia"; break;
            case 4: $ordemTraduzida = "pf.pf_nome DESC, pj.pj_nome_fantasia DESC"; break;
            case 5: $ordemTraduzida = "o.orc_fre_data"; break;
            case 6: $ordemTraduzida = "o.orc_fre_data DESC"; break;
            case 7: $ordemTraduzida = "apf.pf_nome"; break;
            case 8: $ordemTraduzida = "apf.pf_nome DESC"; break;
            case 9: $ordemTraduzida = "o.orc_fre_validade"; break;
            case 10: $ordemTraduzida = "o.orc_fre_validade DESC"; break;
            case 11: $ordemTraduzida = "o.orc_fre_valor"; break;
            case 12: $ordemTraduzida = "o.orc_fre_valor DESC"; break;
        }

        return $ordemTraduzida;
    }

    public function obterClientes()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $clientes = Cliente::getAll();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Cliente $cliente */
        foreach ($clientes as $cliente) {
            $serial[] = $cliente->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function gerarRelatorioOrcamentos(string $filtro, string $inicio, string $fim, int $cliente, int $ordem): string
    {
        if (!Banco::getInstance()->open())
            return "Erro ao conectar no banco de dados.";

        $parametrizacao = Parametrizacao::get();

        $orcamentos = [];

        $tituto = "RELATÓRIO DE ORÇAMENTOS DE FRETE";
        $filtros = "";

        if ($filtro === '' && $inicio === '' && $fim === '' && $cliente === 0) {
            $orcamentos = (new OrcamentoFrete)->findAll($this->traduzirOrdem($ordem));
            $filtros = "SEM FILTRAGEM";
        } else {
            if ($filtro !== '' && $inicio !== '' && $fim !== '' && $cliente !== 0) {
                $orcamentos = (new OrcamentoFrete)->findByFilterPeriodClient($filtro, $inicio, $fim, $cliente, $this->traduzirOrdem($ordem));
                $cli = $orcamentos[0]->getCliente()->getTipo() === 1
                    ? $orcamentos[0]->getCliente()->getPessoaFisica()->getNome()
                    : $orcamentos[0]->getCliente()->getPessoaJuridica()->getNomeFantasia();
                $filtros = "FILTRADO POR FILTRO ($filtro), PERÍODO ($inicio) - ($fim) E CLIENTE ($cli)";
            } else {
                if ($filtro !== '' && $inicio !== '' && $fim !== '' && $cliente === 0) {
                    $orcamentos = (new OrcamentoFrete)->findByFilterPeriod($filtro, $inicio, $fim, $this->traduzirOrdem($ordem));
                    $filtros = "FILTRADO POR FILTRO ($filtro) E PERÍODO ($inicio) - ($fim)";
                } else {
                    if ($filtro !== '' && $inicio === '' && $fim === '' && $cliente !== 0) {
                        $orcamentos = (new OrcamentoFrete)->findByFilterClient($filtro, $cliente, $this->traduzirOrdem($ordem));
                        $cli = $orcamentos[0]->getCliente()->getTipo() === 1
                            ? $orcamentos[0]->getCliente()->getPessoaFisica()->getNome()
                            : $orcamentos[0]->getCliente()->getPessoaJuridica()->getNomeFantasia();
                        $filtros = "FILTRADO POR FILTRO ($filtro) E CLIENTE ($cli)";
                    } else {
                        if ($filtro !== '' && $inicio === '' && $fim === '' && $cliente === 0) {
                            $orcamentos = (new OrcamentoFrete)->findByFilter($filtro, $this->traduzirOrdem($ordem));
                            $filtros = "FILTRADO POR FILTRO ($filtro)";
                        } else {
                            if ($filtro === '' && $inicio !== '' && $fim !== '' && $cliente !== 0) {
                                $orcamentos = (new OrcamentoFrete)->findByPeriodClient($inicio, $fim, $cliente, $this->traduzirOrdem($ordem));
                                $cli = $orcamentos[0]->getCliente()->getTipo() === 1
                                    ? $orcamentos[0]->getCliente()->getPessoaFisica()->getNome()
                                    : $orcamentos[0]->getCliente()->getPessoaJuridica()->getNomeFantasia();
                                $filtros = "FILTRADO POR PERÍODO ($inicio) - ($fim) E CLIENTE ($cli)";
                            } else {
                                if ($filtro === '' && $inicio !== '' && $fim !== '' && $cliente === 0) {
                                    $orcamentos = (new OrcamentoFrete)->findByPeriod($inicio, $fim, $this->traduzirOrdem($ordem));
                                    $filtros = "FILTRADO POR PERÍODO ($inicio) - ($fim)";
                                } else {
                                    if ($filtro === '' && $inicio === '' && $fim === '' && $cliente !== 0) {
                                        $orcamentos = (new OrcamentoFrete)->findByFilterPeriodClient($filtro, $inicio, $fim, $cliente, $this->traduzirOrdem($ordem));
                                        $cli = $orcamentos[0]->getCliente()->getTipo() === 1
                                            ? $orcamentos[0]->getCliente()->getPessoaFisica()->getNome()
                                            : $orcamentos[0]->getCliente()->getPessoaJuridica()->getNomeFantasia();
                                        $filtros = "FILTRADO POR FILTRO ($filtro), PERÍODO ($inicio) - ($fim) E CLIENTE ($cli)";
                                    } else {
                                        echo "as datas de início e fim deve estar preenchidas.";
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
        $col3 = utf8_decode("CLIENTE");
        $col5 = utf8_decode("DATA");
        $col6 = utf8_decode("DESTINO");
        $col7 = utf8_decode("AUTOR");
        $col8 = utf8_decode("VENCIMENTO");
        $col9 = utf8_decode("VALOR (R$)");

        $relatorio->SetFont("Arial", "B", 8);
        $relatorio->SetXY(10, 40);
        $relatorio->Cell(10, 4, $col1, "B");
        $relatorio->Cell(68,4, $col2, "B");
        $relatorio->Cell(40, 4, $col3, "B");
        $relatorio->Cell(21, 4, $col5, "B");
        $relatorio->Cell(40, 4, $col6, "B");
        $relatorio->Cell(40, 4, $col7, "B");
        $relatorio->Cell(27, 4, $col8, "B");
        $relatorio->Cell(24, 4, $col9, "B");

        $y = 46;
        $relatorio->SetFont("Arial", "", 8);
        /** @var OrcamentoFrete $orcamento */
        foreach ($orcamentos as $orcamento) {
            $cod = $orcamento->getId();
            $des = $orcamento->getDescricao();
            $cli = $orcamento->getCliente()->getTipo() === 1
                ? $orcamento->getCliente()->getPessoaFisica()->getNome()
                : $orcamento->getCliente()->getPessoaJuridica()->getNomeFantasia();
            $dat = $orcamento->getData();
            $dst = $orcamento->getDestino()->getNome() . "/" . $orcamento->getDestino()->getEstado()->getSigla();
            $aut = $orcamento->getAutor()->getFuncionario()->getPessoa()->getNome();
            $for = $orcamento->getValidade();
            $vlr = $orcamento->getValor();

            $col1 = utf8_decode("$cod");
            $col2 = utf8_decode("$des");
            $col3 = utf8_decode("$cli");
            $col5 = utf8_decode("$dat");
            $col6 = utf8_decode("$dst");
            $col7 = utf8_decode("$aut");
            $col8 = utf8_decode("$for");
            $col9 = utf8_decode("$vlr");

            $relatorio->SetXY(10, $y);
            $relatorio->Cell(10, 4, $col1);
            $relatorio->Cell(68,4, $col2);
            $relatorio->Cell(40, 4, $col3);
            $relatorio->Cell(21, 4, $col5);
            $relatorio->Cell(40, 4, $col6);
            $relatorio->Cell(40, 4, $col7);
            $relatorio->Cell(27, 4, $col8);
            $relatorio->Cell(24, 4, $col9);

            $y += 6;
        }

        $data = date("dmY");
        $hora = date("His");

        return $relatorio->Output("I", "RelatorioOrcamentosVenda-$data-$hora");
    }
}