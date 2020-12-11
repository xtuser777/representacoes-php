<?php


namespace scr\control;


use scr\model\Cliente;
use scr\model\Parametrizacao;
use scr\model\PedidoFrete;
use scr\model\Status;
use scr\util\Banco;
use scr\util\Retatorio;

class RelatorioPedidoFreteControl
{
    public function obter(int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findAll($this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorCliente(int $client, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByClient($client, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorStatus(int $status, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByStatus($status, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorStatusCliente(int $status, int $client, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByStatusClient($status, $client, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltro(string $filtro, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByFilter($filtro, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroCliente(string $filtro, int $client, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByFilterClient($filtro, $client, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroStatus(string $filtro, int $status, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByFilterStatus($filtro, $status, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroStatusCliente(string $filtro, int $status, int $client, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByFilterStatusClient($filtro, $status, $client, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodo(string $dataInicio, string $dataFim, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByPeriod($dataInicio, $dataFim, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoCliente(string $dataInicio, string $dataFim, int $cliente, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByPeriodClient($dataInicio, $dataFim, $cliente, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoStatus(string $dataInicio, string $dataFim, int $status, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByPeriodStatus($dataInicio, $dataFim, $status, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoStatusCliente(string $dataInicio, string $dataFim, int $status, int $cliente, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByPeriodStatusClient($dataInicio, $dataFim, $status, $cliente, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodo(string $filtro, string $dataInicio, string $dataFim, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByFilterPeriod($filtro, $dataInicio, $dataFim, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoCliente(string $filtro, string $dataInicio, string $dataFim, int $cliente, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByFilterPeriodClient($filtro, $dataInicio, $dataFim, $cliente, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoStatus(string $filtro, string $dataInicio, string $dataFim, int $status, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByFilterPeriodStatus($filtro, $dataInicio, $dataFim, $status, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodoStatusCliente(string $filtro, string $dataInicio, string $dataFim, int $status, int $cliente, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $pedidos = (new PedidoFrete())->findByFilterPeriodStatusClient($filtro, $dataInicio, $dataFim, $status, $cliente, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $pedido PedidoFrete */
        foreach ($pedidos as $pedido) {
            $serial[] = $pedido->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function traduzirOrdem(int $ordem)
    {
        $ordemTraduzida = "";

        switch ($ordem) {
            case 1: $ordemTraduzida = "pfr.ped_fre_descricao"; break;
            case 2: $ordemTraduzida = "pfr.ped_fre_descricao DESC"; break;
            case 3: $ordemTraduzida = "pfr.ped_fre_data"; break;
            case 4: $ordemTraduzida = "pfr.ped_fre_data DESC"; break;
            case 5: $ordemTraduzida = "autor_pf.pf_nome"; break;
            case 6: $ordemTraduzida = "autor_pf.pf_nome DESC"; break;
            case 7: $ordemTraduzida = "fp.for_pag_descricao"; break;
            case 8: $ordemTraduzida = "fp.for_pag_descricao DESC"; break;
            case 9: $ordemTraduzida = "st.sts_descricao"; break;
            case 10: $ordemTraduzida = "st.sts_descricao DESC"; break;
            case 11: $ordemTraduzida = "pfr.ped_fre_valor"; break;
            case 12: $ordemTraduzida = "pfr.ped_fre_valor DESC"; break;
        }

        return $ordemTraduzida;
    }

    public function obterStatus()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $status = (new Status())->findAll();

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var $stat Status */
        foreach ($status as $stat) {
            $serial[] = $stat->jsonSerialize();
        }

        return json_encode($serial);
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

    public function gerarRelatorioPedidos(string $filtro, string $inicio, string $fim, int $status, int $cliente, int $ordem): string
    {
        if (!Banco::getInstance()->open())
            return "Erro ao conectar no banco de dados.";

        $parametrizacao = Parametrizacao::get();

        $pedidos = [];

        $tituto = "RELATÓRIO DE PEDIDOS DE FRETE";
        $filtros = "";

        if ($filtro === '' && $inicio === '' && $fim === '' && $status === 0 && $cliente === 0) {
            $pedidos = (new PedidoFrete())->findAll($ordem);
            $filtros = "SEM FILTRAGEM";
        } else {
            if ($filtro !== '' && $inicio !== '' && $fim !== '' && $status > 0 && $cliente > 0) {
                $pedidos = (new PedidoFrete())->findByFilterPeriodStatusClient($filtro, $inicio, $fim, $status, $cliente, $ordem);
                $sts = $pedidos[0]->getStatus()->getStatus()->getDescricao();
                $cli = $pedidos[0]->getCliente()->getTipo() === 1
                    ? $pedidos[0]->getCliente()->getPessoaFisica()->getNome()
                    : $pedidos[0]->getCliente()->getPessoaJuridica()->getNomeFantasia();
                $filtros = "FILTRADO POR FILTRO ($filtro), PERÍODO ($inicio) - ($fim), STATUS ($sts) E CLIENTE ($cli)";
            } else {
                if ($filtro !== '' && $inicio !== '' && $fim !== '' && $status > 0 && $cliente === 0) {
                    $pedidos = (new PedidoFrete())->findByFilterPeriodStatus($filtro, $inicio, $fim, $status, $ordem);
                    $sts = $pedidos[0]->getStatus()->getStatus()->getDescricao();
                    $filtros = "FILTRADO POR FILTRO ($filtro), PERÍODO ($inicio) - ($fim) E STATUS ($sts)";
                } else {
                    if ($filtro !== '' && $inicio !== '' && $fim !== '' && $status === 0 && $cliente > 0) {
                        $pedidos = (new PedidoFrete())->findByFilterPeriodClient($filtro, $inicio, $fim, $cliente, $ordem);
                        $cli = $pedidos[0]->getCliente()->getTipo() === 1
                            ? $pedidos[0]->getCliente()->getPessoaFisica()->getNome()
                            : $pedidos[0]->getCliente()->getPessoaJuridica()->getNomeFantasia();
                        $filtros = "FILTRADO POR FILTRO ($filtro), PERÍODO ($inicio) - ($fim) E CLIENTE ($cli)";
                    } else {
                        if ($filtro !== '' && $inicio !== '' && $fim !== '' && $status === 0 && $cliente === 0) {
                            $pedidos = (new PedidoFrete())->findByFilterPeriod($filtro, $inicio, $fim, $ordem);
                            $filtros = "FILTRADO POR FILTRO ($filtro) E PERÍODO ($inicio) - ($fim)";
                        } else {
                            if ($filtro !== '' && $inicio === '' && $fim === '' && $status > 0 && $cliente > 0) {
                                $pedidos = (new PedidoFrete())->findByFilterStatusClient($filtro, $status, $cliente, $ordem);
                                $sts = $pedidos[0]->getStatus()->getStatus()->getDescricao();
                                $cli = $pedidos[0]->getCliente()->getTipo() === 1
                                    ? $pedidos[0]->getCliente()->getPessoaFisica()->getNome()
                                    : $pedidos[0]->getCliente()->getPessoaJuridica()->getNomeFantasia();
                                $filtros = "FILTRADO POR FILTRO ($filtro), STATUS ($sts) E CLIENTE ($cli)";
                            } else {
                                if ($filtro !== '' && $inicio === '' && $fim === '' && $status > 0 && $cliente === 0) {
                                    $pedidos = (new PedidoFrete())->findByFilterStatus($filtro, $status, $ordem);
                                    $sts = $pedidos[0]->getStatus()->getStatus()->getDescricao();
                                    $filtros = "FILTRADO POR FILTRO ($filtro) E STATUS ($sts)";
                                } else {
                                    if ($filtro !== '' && $inicio === '' && $fim === '' && $status === 0 && $cliente > 0) {
                                        $pedidos = (new PedidoFrete())->findByFilterClient($filtro, $cliente, $ordem);
                                        $cli = $pedidos[0]->getCliente()->getTipo() === 1
                                            ? $pedidos[0]->getCliente()->getPessoaFisica()->getNome()
                                            : $pedidos[0]->getCliente()->getPessoaJuridica()->getNomeFantasia();
                                        $filtros = "FILTRADO POR FILTRO ($filtro) E CLIENTE ($cli)";
                                    } else {
                                        if ($filtro !== '' && $inicio === '' && $fim === '' && $status === 0 && $cliente === 0) {
                                            $pedidos = (new PedidoFrete())->findByFilter($filtro, $ordem);
                                            $filtros = "FILTRADO POR FILTRO ($filtro)";
                                        } else {
                                            if ($filtro === '' && $inicio !== '' && $fim !== '' && $status > 0 && $cliente > 0) {
                                                $pedidos = (new PedidoFrete())->findByPeriodStatusClient($inicio, $fim, $status, $cliente, $ordem);
                                                $sts = $pedidos[0]->getStatus()->getStatus()->getDescricao();
                                                $cli = $pedidos[0]->getCliente()->getTipo() === 1
                                                    ? $pedidos[0]->getCliente()->getPessoaFisica()->getNome()
                                                    : $pedidos[0]->getCliente()->getPessoaJuridica()->getNomeFantasia();
                                                $filtros = "FILTRADO POR PERÍODO ($inicio) - ($fim), STATUS ($sts) E CLIENTE ($cli)";
                                            } else {
                                                if ($filtro === '' && $inicio !== '' && $fim !== '' && $status > 0 && $cliente === 0) {
                                                    $pedidos = (new PedidoFrete())->findByPeriodStatus($inicio, $fim, $status, $ordem);
                                                    $sts = $pedidos[0]->getStatus()->getStatus()->getDescricao();
                                                    $filtros = "FILTRADO POR PERÍODO ($inicio) - ($fim) E STATUS ($sts)";
                                                } else {
                                                    if ($filtro === '' && $inicio !== '' && $fim !== '' && $status === 0 && $cliente > 0) {
                                                        $pedidos = (new PedidoFrete())->findByPeriodClient($inicio, $fim, $cliente, $ordem);
                                                        $cli = $pedidos[0]->getCliente()->getTipo() === 1
                                                            ? $pedidos[0]->getCliente()->getPessoaFisica()->getNome()
                                                            : $pedidos[0]->getCliente()->getPessoaJuridica()->getNomeFantasia();
                                                        $filtros = "FILTRADO POR PERÍODO ($inicio) - ($fim) E CLIENTE ($cli)";
                                                    } else {
                                                        if ($filtro === '' && $inicio !== '' && $fim !== '' && $status === 0 && $cliente === 0) {
                                                            $pedidos = (new PedidoFrete())->findByPeriod($inicio, $fim, $ordem);
                                                            $filtros = "FILTRADO POR PERÍODO ($inicio) - ($fim)";
                                                        } else {
                                                            if ($filtro === '' && $inicio === '' && $fim === '' && $status > 0 && $cliente > 0) {
                                                                $pedidos = (new PedidoFrete())->findByStatusClient($status, $cliente, $ordem);
                                                                $sts = $pedidos[0]->getStatus()->getStatus()->getDescricao();
                                                                $cli = $pedidos[0]->getCliente()->getTipo() === 1
                                                                    ? $pedidos[0]->getCliente()->getPessoaFisica()->getNome()
                                                                    : $pedidos[0]->getCliente()->getPessoaJuridica()->getNomeFantasia();
                                                                $filtros = "FILTRADO POR STATUS ($sts) E CLIENTE ($cli)";
                                                            } else {
                                                                if ($filtro === '' && $inicio === '' && $fim === '' && $status > 0 && $cliente === 0) {
                                                                    $pedidos = (new PedidoFrete())->findByStatus($status, $ordem);
                                                                    $sts = $pedidos[0]->getStatus()->getStatus()->getDescricao();
                                                                    $filtros = "FILTRADO POR STATUS ($sts)";
                                                                } else {
                                                                    if ($filtro === '' && $inicio === '' && $fim === '' && $status === 0 && $cliente > 0) {
                                                                        $pedidos = (new PedidoFrete())->findByClient($cliente, $ordem);
                                                                        $cli = $pedidos[0]->getCliente()->getTipo() === 1
                                                                            ? $pedidos[0]->getCliente()->getPessoaFisica()->getNome()
                                                                            : $pedidos[0]->getCliente()->getPessoaJuridica()->getNomeFantasia();
                                                                        $filtros = "FILTRADO POR CLIENTE ($cli)";
                                                                    } else {
                                                                        exit("As datas de início e fim precisam estar preenchidas.");
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

        $col1 = utf8_decode("CÓD.");
        $col2 = utf8_decode("DESCRIÇÃO");
        $col3 = utf8_decode("CLIENTE");
        $col4 = utf8_decode("MOTORISTA");
        $col5 = utf8_decode("DATA");
        $col6 = utf8_decode("DESTINO");
        $col7 = utf8_decode("AUTOR");
        $col8 = utf8_decode("FORMA PAGAMENTO");
        $col9 = utf8_decode("VALOR (R$)");

        $relatorio->SetFont("Arial", "B", 8);
        $relatorio->SetXY(10, 40);
        $relatorio->Cell(10, 4, $col1, "B");
        $relatorio->Cell(54,4, $col2, "B");
        $relatorio->Cell(37, 4, $col3, "B");
        $relatorio->Cell(37, 4, $col4, "B");
        $relatorio->Cell(21, 4, $col5, "B");
        $relatorio->Cell(31, 4, $col6, "B");
        $relatorio->Cell(30, 4, $col7, "B");
        $relatorio->Cell(36, 4, $col8, "B");
        $relatorio->Cell(21, 4, $col9, "B");

        $y = 46;
        $relatorio->SetFont("Arial", "", 8);
        /** @var PedidoFrete $pedido */
        foreach ($pedidos as $pedido) {
            $cod = $pedido->getId();
            $des = $pedido->getDescricao();
            $cli = $pedido->getCliente()->getTipo() === 1
                ? $pedido->getCliente()->getPessoaFisica()->getNome()
                : $pedido->getCliente()->getPessoaJuridica()->getNomeFantasia();
            $mot = $pedido->getMotorista()->getPessoa()->getNome();
            $dat = $pedido->getData();
            $dst = $pedido->getDestino()->getNome() . "/" . $pedido->getDestino()->getEstado()->getSigla();
            $aut = $pedido->getAutor()->getFuncionario()->getPessoa()->getNome();
            $for = $pedido->getFormaPagamentoFrete()->getDescricao();
            $vlr = $pedido->getValor();

            $col1 = utf8_decode("$cod");
            $col2 = utf8_decode("$des");
            $col3 = utf8_decode("$cli");
            $col4 = utf8_decode("$mot");
            $col5 = utf8_decode("$dat");
            $col6 = utf8_decode("$dst");
            $col7 = utf8_decode("$aut");
            $col8 = utf8_decode("$for");
            $col9 = utf8_decode("$vlr");

            $relatorio->SetXY(10, $y);
            $relatorio->Cell(10, 4, $col1);
            $relatorio->Cell(54,4, $col2);
            $relatorio->Cell(37, 4, $col3);
            $relatorio->Cell(37, 4, $col4);
            $relatorio->Cell(21, 4, $col5);
            $relatorio->Cell(31, 4, $col6);
            $relatorio->Cell(30, 4, $col7);
            $relatorio->Cell(36, 4, $col8);
            $relatorio->Cell(21, 4, $col9);

            $y += 6;
        }

        $data = date("dmY");
        $hora = date("His");

        return $relatorio->Output("I", "RelatorioPedidosFrete-$data-$hora");
    }
}