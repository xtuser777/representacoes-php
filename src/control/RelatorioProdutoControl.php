<?php


namespace scr\control;


use scr\model\Parametrizacao;
use scr\model\Produto;
use scr\model\Representacao;
use scr\util\Banco;
use scr\util\Retatorio;

class RelatorioProdutoControl
{
    public function obterRepresentacoes()
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $representacoes = Representacao::getAll();

        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var Representacao $representacao */
        foreach ($representacoes as $representacao) {
            $jarray[] = $representacao->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obter(int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $produtos = Produto::findAll($this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var Produto $produto */
        foreach ($produtos as $produto) {
            $jarray[] = $produto->jsonSerialize();
        }

        return json_encode($jarray);
    }

    public function obterPorFiltros(string $filtro, string $unidade, int $representacao, int $ordem)
    {
        if (!Banco::getInstance()->open())
            return json_encode([]);

        $produtos = Produto::findByFilters($filtro, $unidade, $representacao, $this->traduzirOrdem($ordem));

        Banco::getInstance()->getConnection()->close();

        $jarray = [];
        /** @var Produto $produto */
        foreach ($produtos as $produto) {
            $jarray[] = $produto->jsonSerialize();
        }

        return json_encode($jarray);
    }

    private function traduzirOrdem(int $ordem): string
    {
        switch ($ordem) {
            case 1: return "p.pro_descricao";
            case 2: return "p.pro_descricao DESC";
            case 3: return "p.pro_medido";
            case 4: return "p.pro_medida DESC";
            case 5: return "p.pro_peso";
            case 6: return "p.pro_peso DESC";
            case 7: return "p.pro_preco";
            case 8: return "p.pro_preco DESC";
            case 9: return "pj.pj_nome_fantasia";
            case 10: return "pj.pj_nome_fantasia DESC";
            default: return "";
        }
    }

    public function gerarRelatorioProdutos(string $filtro, string $unidade, int $representacao, int $ordem): string
    {
        if (!Banco::getInstance()->open())
            return "Erro ao conectar no banco de dados.";

        $parametrizacao = Parametrizacao::get();

        $produtos = [];

        $tituto = "RELATÓRIO DE PRODUTOS";
        $filtros = "";

        if ($filtro === '' && $unidade === '' && $representacao === 0) {
            $produtos = Produto::findAll($this->traduzirOrdem($ordem));
            $filtros = "SEM FILTRAGEM";
        } else {
            $produtos = Produto::findByFilters($filtro, $unidade, $representacao, $this->traduzirOrdem($ordem));
            $filtros = "FILTRADO POR";

            if ($filtro !== "") {
                $filtros .= " FILTRO ($filtro)";
            }

            if ($unidade !== "") {
                if (strlen($filtros) > 12)
                    $filtros .= ",";
                $filtros .= " MEDIDA ($unidade)";
            }

            if ($representacao > 0) {
                if (strlen($filtros) > 12)
                    $filtros .= ",";
                $rep = $produtos[0]->getRepresentacao()->getPessoa()->getNomeFantasia();
                $filtros .= " REPRESENTAÇÃO ($rep)";
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

        $col1 = utf8_decode("CÓDIGO");
        $col2 = utf8_decode("DESCRIÇÃO");
        $col3 = utf8_decode("MEDIDA");
        $col4 = utf8_decode("PESO");
        $col5 = utf8_decode("PREÇO");
        $col6 = utf8_decode("REPRESENTAÇÃO");

        $relatorio->SetFont("Arial", "B", 9);
        $relatorio->SetXY(10, 40);
        $relatorio->Cell(17, 4, $col1, "B");
        $relatorio->Cell(100,4, $col2, "B");
        $relatorio->Cell(32, 4, $col3, "B");
        $relatorio->Cell(30, 4, $col4, "B");
        $relatorio->Cell(30, 4, $col5, "B");
        $relatorio->Cell(67, 4, $col6, "B");

        $y = 46;
        $relatorio->SetFont("Arial", "", 9);
        /** @var Produto $produto */
        foreach ($produtos as $produto) {
            $cod = $produto->getId();
            $des = $produto->getDescricao();
            $med = $produto->getMedida();
            $pes = $produto->getPeso();
            $pre = $produto->getPreco();
            $rep = $produto->getRepresentacao()->getPessoa()->getNomeFantasia();

            $col1 = utf8_decode("$cod");
            $col2 = utf8_decode("$des");
            $col3 = utf8_decode("$med");
            $col4 = utf8_decode("$pes");
            $col5 = utf8_decode("$pre");
            $col6 = utf8_decode("$rep");

            $relatorio->SetXY(10, $y);
            $relatorio->Cell(17, 4, $col1);
            $relatorio->Cell(100,4, $col2);
            $relatorio->Cell(32, 4, $col3);
            $relatorio->Cell(30, 4, $col4);
            $relatorio->Cell(30, 4, $col5);
            $relatorio->Cell(67, 4, $col6);

            $y += 6;
        }

        $data = date("dmY");
        $hora = date("His");

        return $relatorio->Output("I", "RelatorioProdutos-$data-$hora");
    }
}