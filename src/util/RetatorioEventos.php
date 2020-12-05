<?php


namespace scr\util;


use FPDF;
use scr\model\Evento;

class RetatorioEventos extends FPDF
{
    private $parametrizacao;

    public function __construct($orientation = 'L', $unit = 'mm', $size = 'A4', $parametrizacao = null)
    {
        parent::__construct($orientation, $unit, $size);
        $this->parametrizacao = $parametrizacao;
    }

    function Header()
    {
        $empresa = utf8_decode($this->parametrizacao->pessoa->razaoSocial);
        $contato = utf8_decode($this->parametrizacao->pessoa->contato->endereco->rua . ", " . $this->parametrizacao->pessoa->contato->endereco->numero . " - " . $this->parametrizacao->pessoa->contato->endereco->complemento);
        $documento = utf8_decode($this->parametrizacao->pessoa->cnpj);

        $this->Image(ROOT . "/static/images/logo.png", 9, 4, 18, 18);

        $this->SetFont("Arial", "B", 14);
        $w1 = $this->GetStringWidth($empresa);
        $this->SetX(287-$w1);
        $this->Cell($w1, 1, $empresa, 0, 1, "R");

        $this->Ln(5);

        $this->SetFont("Arial", "", 10);
        $w2 = $this->GetStringWidth($contato);
        $this->SetX(287-$w2);
        $this->Cell($w2, 1, $contato, 0, 1, "R");

        $this->Ln(4);

        $this->SetFont("Arial", "", 9);
        $w3= $this->GetStringWidth($documento);
        $this->SetX(287-$w3);
        $this->Cell($w3, 1, $documento, 0, 1, "R");

        $this->Ln(2);

        $this->SetLineWidth(0.5);
        $this->Line(7, 25, 290, 25);
    }

    function Footer()
    {
        $date = utf8_decode(date("d/m/Y H:i:s"));
        $endereco = utf8_decode(
            $this->parametrizacao->pessoa->contato->endereco->rua
            . ", " .
            $this->parametrizacao->pessoa->contato->endereco->numero
            . " - " .
            $this->parametrizacao->pessoa->contato->endereco->complemento
            . " - " .
            $this->parametrizacao->pessoa->contato->endereco->bairro
            . " - " .
            $this->parametrizacao->pessoa->contato->endereco->cidade->nome
            . " - " .
            $this->parametrizacao->pessoa->contato->endereco->cidade->estado->sigla
        );
        $contato = utf8_decode(
            $this->parametrizacao->pessoa->contato->telefone
            . " - " .
            $this->parametrizacao->pessoa->contato->email
        );
        $page = utf8_decode("Página " . $this->PageNo());

        $this->SetLineWidth(0.5);
        $this->Line(7, ($this->GetPageHeight() - 16), 290, ($this->GetPageHeight() - 16));

        $this->Ln(2);

        $this->SetFont("Times", "", 9);
        $this->Text(8, ($this->GetPageHeight() - 12), $date);

        $this->SetFont("Arial", "", 9);
        $this->Text(((297 - $this->GetStringWidth($endereco)) / 2), ($this->GetPageHeight() - 12), $endereco);
        $this->Text(((297 - $this->GetStringWidth($contato)) / 2), ($this->GetPageHeight() - 8), $contato);

        $this->SetFont("Times", "", 9);
        $this->Text((289 - $this->GetStringWidth($page)), ($this->GetPageHeight() - 12), $page);
    }

    function TituloRelatorio(string $filtro)
    {
        $titulo = utf8_decode("RELATÓRIO DE EVENTOS DO SISTEMA$filtro");

        $this->SetFont("Arial", "B", 12);
        $this->Text(((297 - $this->GetStringWidth($titulo)) / 2), 32, $titulo);
    }

    function CabecalhoTabela()
    {
        $col1 = utf8_decode("CÓDIGO");
        $col2 = utf8_decode("DESCRIÇÃO");
        $col3 = utf8_decode("DATA");
        $col4 = utf8_decode("HORA");
        $col5 = utf8_decode("PEDIDO");
        $col6 = utf8_decode("AUTOR");

        $this->SetFont("Arial", "B", 10);
        $this->SetXY(10, 36);
        $this->Cell(18, 4, $col1, "B");
        $this->Cell(100,4, $col2, "B");
        $this->Cell(15, 4, $col3, "B");
        $this->Cell(15, 4, $col4, "B");
        $this->Cell(68, 4, $col5, "B");
        $this->Cell(60, 4, $col6, "B");
    }
}
