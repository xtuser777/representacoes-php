<?php


namespace scr\control;


use scr\model\Evento;
use scr\model\Parametrizacao;
use scr\util\Banco;
use scr\model\Endereco;
use scr\model\Contato;
use scr\model\PessoaFisica;
use scr\model\PessoaJuridica;
use scr\model\Cliente;
use scr\util\Retatorio;

class RelatorioClienteControl
{
    public function obter(int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $array = Cliente::getAll($this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        for ($i = 0; $i < count($array); $i++) {
            /** @var Cliente $cli */
            $cli = $array[$i];
            $jarray[] = $cli->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorId(int $id)
    {
        if (!Banco::getInstance()->open())
            return json_encode(null);

        $array = Cliente::getById($id);

        Banco::getInstance()->getConnection()->close();

        return json_encode($array);
    }

    public function obterPorFiltroPeriodoTipo(string $filtro, string $inicio, string $fim, int $tipo, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $clientes = Cliente::getByFilterPeriodType($filtro, $inicio, $fim, $tipo, $ordem);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Cliente $cliente */
        foreach ($clientes as $cliente) {
            $serial[] = $cliente->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroPeriodo(string $filtro, string $inicio, string $fim, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $clientes = Cliente::getByFilterPeriod($filtro, $inicio, $fim, $ordem);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Cliente $cliente */
        foreach ($clientes as $cliente) {
            $serial[] = $cliente->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltroTipo(string $filtro, int $tipo, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $clientes = Cliente::getByFilterType($filtro, $tipo, $ordem);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Cliente $cliente */
        foreach ($clientes as $cliente) {
            $serial[] = $cliente->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorFiltro(string $filtro, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $clientes = Cliente::getByFilter($filtro, $ordem);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Cliente $cliente */
        foreach ($clientes as $cliente) {
            $serial[] = $cliente->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodoTipo(string $inicio, string $fim, int $tipo, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $clientes = Cliente::getByPeriodType($inicio, $fim, $tipo, $ordem);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Cliente $cliente */
        foreach ($clientes as $cliente) {
            $serial[] = $cliente->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorPeriodo(string $inicio, string $fim, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $clientes = Cliente::getByPeriod($inicio, $fim, $ordem);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Cliente $cliente */
        foreach ($clientes as $cliente) {
            $serial[] = $cliente->jsonSerialize();
        }

        return json_encode($serial);
    }

    public function obterPorTipo(int $tipo, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $clientes = Cliente::getByType($tipo, $ordem);

        Banco::getInstance()->getConnection()->close();

        $serial = [];
        /** @var Cliente $cliente */
        foreach ($clientes as $cliente) {
            $serial[] = $cliente->jsonSerialize();
        }

        return json_encode($serial);
    }

    private function traduzirOrdem(int $ordem) : string
    {
        switch ($ordem) {
            case 1: return "pf.pf_nome, pj.pj_nome_fantasia";
            case 2: return "pf.pf_nome DESC, pj.pj_nome_fantasia DESC";
            case 3: return "pf.pf_cpf, pj.pj_cnpj";
            case 4: return "pf.pf_cpf DESC, pj.pj_cnpj DESC";
            case 5: return "cl.cli_cadastro";
            case 6: return "cl.cli_cadastro DESC";
            case 7: return "ct.ctt_telefone";
            case 8: return "ct.ctt_telefone DESC";
            case 9: return "ct.ctt_celular";
            case 10: return "ct.ctt_celular DESC";
            case 11: return "cl.cli_tipo";
            case 12: return "cl.cli_tipo DESC";
            case 13: return "ct.ctt_email";
            case 14: return "ct.ctt_email DESC";
            default: return "";
        }
    }

    public function gerarRelatorioClientes(string $filtro, string $inicio, string $fim, int $tipo, int $ordem): string
    {
        if (!Banco::getInstance()->open())
            return "Erro ao conectar no banco de dados.";

        $parametrizacao = Parametrizacao::get();

        $clientes = [];

        $tituto = "RELATÓRIO DE CLIENTES";
        $filtros = "";

        if ($filtro === '' && $inicio === '' && $fim === '' && $tipo === 0) {
            $clientes = Cliente::getAll($ordem);
            $filtros = "SEM FILTRAGEM";
        } else {
            if ($filtro !== '' && $inicio !== '' && $fim !== '' && $tipo !== 0) {
                $clientes = Cliente::getByFilterPeriodType($filtro, $inicio, $fim, $tipo, $ordem);
                $tip = $tipo === 1 ? "FÍSICA": "JURÍDICA";
                $filtros = "FILTRADO POR FILTRO ($filtro), PERÍODO ($inicio) - ($fim) E TIPO ($tip)";
            } else {
                if ($filtro !== '' && $inicio !== '' && $fim !== '' && $tipo === 0) {
                    $clientes = Cliente::getByFilterPeriod($filtro, $inicio, $fim, $ordem);
                    $filtros = "FILTRADO POR FILTRO ($filtro) E PERÍODO ($inicio) - ($fim)";
                } else {
                    if ($filtro !== '' && $inicio === '' && $fim === '' && $tipo !== 0) {
                        $clientes = Cliente::getByFilterType($filtro, $tipo, $ordem);
                        $tip = $tipo === 1 ? "FÍSICA": "JURÍDICA";
                        $filtros = "FILTRADO POR FILTRO ($filtro) E TIPOV";
                    } else {
                        if ($filtro !== '' && $inicio === '' && $fim === '' && $tipo === 0) {
                            $clientes = Cliente::getByFilter($filtro, $ordem);
                            $filtros = "FILTRADO POR FILTRO ($filtro)";
                        } else {
                            if ($filtro === '' && $inicio !== '' && $fim !== '' && $tipo !== 0) {
                                $clientes = Cliente::getByPeriodType($inicio, $fim, $tipo, $ordem);
                                $tip = $tipo === 1 ? "FÍSICA": "JURÍDICA";
                                $filtros = "FILTRADO POR PERÍODO ($inicio) - ($fim) E TIPO ($tip)";
                            } else {
                                if ($filtro === '' && $inicio !== '' && $fim !== '' && $tipo === 0) {
                                    $clientes = Cliente::getByPeriod($inicio, $fim, $ordem);
                                    $filtros = "FILTRADO POR PERÍODO ($inicio) - ($fim)";
                                } else {
                                    if ($filtro === '' && $inicio === '' && $fim === '' && $tipo !== 0) {
                                        $clientes = Cliente::getByType($tipo, $ordem);
                                        $tip = $tipo === 1 ? "FÍSICA": "JURÍDICA";
                                        $filtros = "FILTRADO POR TIPO ($tip)";
                                    } else {
                                        echo "As datas de início e fim devem estar preenchidas.";
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
        $col2 = utf8_decode("NOME");
        $col3 = utf8_decode("CPF/CNPJ");
        $col4 = utf8_decode("CADASTRO");
        $col5 = utf8_decode("TELEFONE");
        $col6 = utf8_decode("CELULAR");
        $col7 = utf8_decode("TIPO");
        $col8 = utf8_decode("E-MAIL");

        $relatorio->SetFont("Arial", "B", 9);
        $relatorio->SetXY(10, 40);
        $relatorio->Cell(10, 4, $col1, "B");
        $relatorio->Cell(70,4, $col2, "B");
        $relatorio->Cell(32, 4, $col3, "B");
        $relatorio->Cell(22, 4, $col4, "B");
        $relatorio->Cell(24, 4, $col5, "B");
        $relatorio->Cell(26, 4, $col6, "B");
        $relatorio->Cell(18, 4, $col7, "B");
        $relatorio->Cell(74, 4, $col8, "B");

        $y = 46;
        $relatorio->SetFont("Arial", "", 9);
        /** @var Cliente $cliente */
        foreach ($clientes as $cliente) {
            $cod = $cliente->getId();
            $nom = $cliente->getTipo() === 1 ? $cliente->getPessoaFisica()->getNome() : $cliente->getPessoaJuridica()->getNomeFantasia();
            $doc = $cliente->getTipo() === 1 ? $cliente->getPessoaFisica()->getCpf() : $cliente->getPessoaJuridica()->getCnpj();
            $cad = $cliente->getCadastro();
            $tel = $cliente->getTipo() === 1 ? $cliente->getPessoaFisica()->getContato()->getTelefone() : $cliente->getPessoaJuridica()->getContato()->getTelefone();
            $cel = $cliente->getTipo() === 1 ? $cliente->getPessoaFisica()->getContato()->getCelular() : $cliente->getPessoaJuridica()->getContato()->getCelular();
            $tip = $cliente->getTipo() === 1 ? "FÍSICA" : "JURÍDICA";
            $ema = $cliente->getTipo() === 1 ? $cliente->getPessoaFisica()->getContato()->getEmail() : $cliente->getPessoaJuridica()->getContato()->getEmail();

            $col1 = utf8_decode("$cod");
            $col2 = utf8_decode("$nom");
            $col3 = utf8_decode("$doc");
            $col4 = utf8_decode("$cad");
            $col5 = utf8_decode("$tel");
            $col6 = utf8_decode("$cel");
            $col7 = utf8_decode("$tip");
            $col8 = utf8_decode("$ema");

            $relatorio->SetXY(10, $y);
            $relatorio->Cell(10, 4, $col1);
            $relatorio->Cell(70,4, $col2);
            $relatorio->Cell(32, 4, $col3);
            $relatorio->Cell(22, 4, $col4);
            $relatorio->Cell(24, 4, $col5);
            $relatorio->Cell(26, 4, $col6);
            $relatorio->Cell(18, 4, $col7);
            $relatorio->Cell(74, 4, $col8);

            $y += 6;
        }

        $data = date("dmY");
        $hora = date("His");

        return $relatorio->Output("I", "RelatorioClientes-$data-$hora");
    }
}