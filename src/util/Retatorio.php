<?php


namespace scr\util;


use FPDF;

class Retatorio extends FPDF
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

    function TituloRelatorio(string $titulo, string $filtros)
    {
        $title = utf8_decode("$titulo");
        $filters = utf8_decode("\"$filtros\"");

        $this->SetFont("Arial", "B", 12);
        $this->Text(((297 - $this->GetStringWidth($title)) / 2), 31, $title);

        $this->SetFont("Arial", "B", 8);
        $this->Text(((297 - $this->GetStringWidth($filters)) / 2), 36, $filters);
    }
}
