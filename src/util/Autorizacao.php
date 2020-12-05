<?php


namespace scr\util;


use FPDF;

class Autorizacao extends FPDF
{
    private $parametrizacao;

    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4', $parametrizacao = null)
    {
        parent::__construct($orientation, $unit, $size);
        $this->parametrizacao = $parametrizacao;
    }

    function Header()
    {
        $empresa = utf8_decode($this->parametrizacao->pessoa->razaoSocial);
        $contato = utf8_decode($this->parametrizacao->pessoa->contato->endereco->rua . ", " . $this->parametrizacao->pessoa->contato->endereco->numero . " - " . $this->parametrizacao->pessoa->contato->endereco->complemento . " - " . $this->parametrizacao->pessoa->contato->telefone);
        $documento = utf8_decode($this->parametrizacao->pessoa->cnpj);

        $this->SetFont("Times", "BI", 14);
        $w1 = $this->GetStringWidth($empresa);
        $this->SetX(200-$w1);
        $this->Cell($w1, 1, $empresa, 0, 1, "R");

        $this->Ln(5);

        $this->SetFont("Times", "BI", 12);
        $w2 = $this->GetStringWidth($contato);
        $this->SetX(200-$w2);
        $this->Cell($w2, 1, $contato, 0, 1, "R");

        $this->Ln(5);

        $this->SetFont("Times", "BI", 9);
        $w3= $this->GetStringWidth($documento);
        $this->SetX(200-$w3);
        $this->Cell($w3, 1, $documento, 0, 1, "R");

        $this->Ln(2);

        $this->SetLineWidth(0.5);
        $this->Line(7, 25, 203, 25);
    }

    function Footer()
    {
        $date = utf8_decode(date("d/m/Y H:i:s"));
        $email = utf8_decode($this->parametrizacao->pessoa->contato->email);
        $page = utf8_decode("Página " . $this->PageNo());

        $this->SetLineWidth(0.5);
        $this->Line(7, ($this->GetPageHeight() - 18), 203, ($this->GetPageHeight() - 18));

        $this->Ln(2);

        $this->SetFont("Times", "", 9);
        $this->Text(9, ($this->GetPageHeight() - 14), $date);

        $this->SetFont("Times", "BI", 9);
        $this->Text((200 - $this->GetStringWidth($email)), ($this->GetPageHeight() - 15), $email);

        $this->SetFont("Times", "", 9);
        $this->Text((200 - $this->GetStringWidth($page)), ($this->GetPageHeight() - 9), $page);
    }

    function DadosDocumento()
    {
        $data = utf8_decode(date("d, M, Y"));

        $this->SetFont("Arial", "", 10);
        $this->Text(42, 34, $data);
    }

    function DadosRepresentacao(int $pedido, string $nome, string $responsavel)
    {
        $rep = utf8_decode($nome);
        $resp = utf8_decode($responsavel);

        $this->SetFont("Arial", "B", 10);
        $this->Text(12, 49, utf8_decode("À"));

        $this->SetFont("Arial", "", 10);
        $this->Text(12, 54, $rep);

        $this->SetFont("Arial", "B", 10);
        $this->Text(12, 58, utf8_decode("NÚMERO DO PEDIDO DA FÁBRICA:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((12 + $this->GetStringWidth("NÚMERO DO PEDIDO DA FÁBRICA:") + 2), 58, $pedido);

        $this->SetFont("Arial", "B", 10);
        $this->Text(12, 67, utf8_decode("ATT.:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((12 + $this->GetStringWidth("ATT.:") + 2), 67, $resp);
    }

    function TituloDocumento()
    {
        $title = utf8_decode("AUTORIZAÇÃO DE CARREGAMENTO");

        $this->SetFont("Times", "BI", 14);
        $this->Text(((210 - $this->GetStringWidth($title)) / 2), 80, $title);
    }

    function DadosMotorista(int $codigo, string $nome, string $cpf, string $rg, string $cnh, string $placa, string $placaCarroceria)
    {
        $codMot = utf8_decode("$codigo");
        $mot = utf8_decode($nome);
        $cpfMot = utf8_decode($cpf);
        $rgMot = utf8_decode($rg);
        $cnhMot = utf8_decode($cnh);
        $placa = utf8_decode($placa);
        $placaCar = utf8_decode($placaCarroceria);

        $this->SetFont("Arial", "B", 10);
        $this->Text(22, 89, utf8_decode("AUTORIZAMOS O"));

        $this->SetFont("Arial", "B", 10);
        $this->Text(22, 94.5, utf8_decode("SR. MOTORISTA:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((22 + $this->GetStringWidth("SR. MOTORISTA:") + 2), 94.5, utf8_decode("$codMot     $mot"));

        $this->SetFont("Arial", "B", 10);
        $this->Text(22, 100, utf8_decode("CPF:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((22 + $this->GetStringWidth("CPF:") + 2), 100, $cpfMot);

        $this->SetFont("Arial", "B", 10);
        $this->Text(85, 100, utf8_decode("RG:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((85 + $this->GetStringWidth("RG:") + 2), 100, $rgMot);

        $this->SetFont("Arial", "B", 10);
        $this->Text(150, 100, utf8_decode("CNH:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((150 + $this->GetStringWidth("CNH:") + 2), 100, $cnhMot);

        $this->SetFont("Arial", "B", 10);
        $this->Text(22, 105, utf8_decode("PLACA VEIC.:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((22 + $this->GetStringWidth("PLACA VEIC.:") + 2), 105, $placa);

        $this->SetFont("Arial", "B", 10);
        $this->Text(128, 105, utf8_decode("PLACA CARROC.:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((128 + $this->GetStringWidth("PLACA CARROC.:") + 3),105, $placaCar);
    }

    function DadosProprietario(string $proprietario, string $documento)
    {
        $prop = utf8_decode($proprietario);
        $doc = utf8_decode($documento);

        $this->SetFont("Arial", "B", 10);
        $this->Text(22, 111, utf8_decode("PROPRIETÁRIO:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((22 + $this->GetStringWidth("PROPRIETÁRIO:") + 2), 111, $prop);

        $this->SetFont("Arial", "B", 10);
        $this->Text(140.2, 111, utf8_decode("CPF/CNPJ:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((140.2 + $this->GetStringWidth("CPF/CNPJ:") + 2), 111, $doc);
    }

    function DadosCliente(int $codigo, string $nome, string $documento1, string $documento2, string $cidade, string $estado, string $representacao)
    {
        $nome = utf8_decode($nome);
        $cod = utf8_decode("$codigo");
        $doc1 = utf8_decode($documento1);
        $doc2 = utf8_decode($documento2);
        $cid = utf8_decode($cidade);
        $est = utf8_decode($estado);
        $rep = utf8_decode($representacao);

        $this->SetFont("Arial", "B", 10);
        $this->Text(22, 120, utf8_decode("A CARREGAR PARA O(S) CLIENTE(S) ABAIXO DESCRITO(S):"));

        $this->Text(40, 126, utf8_decode("CLIENTE:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((40 + $this->GetStringWidth("CLIENTE:") + 2), 126, $nome);

        $this->SetFont("Arial", "B", 10);
        $this->Text(130, 126, utf8_decode("CÓDIGO:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((130 + $this->GetStringWidth("CÓDIGO:") + 1), 126, $cod);

        $this->SetFont("Arial", "B", 10);
        $this->Text(37.5, 130.5, utf8_decode("CPF/CNPJ:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((37.5 + $this->GetStringWidth("CPF/CNPJ:") + 2), 130.5, $doc1);

        $this->SetFont("Arial", "B", 10);
        $this->Text(134.5, 130.5, utf8_decode("RG/IE:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((134.5 + $this->GetStringWidth("RG/IE:") + 2), 130.5, $doc2);

        $this->SetFont("Arial", "B", 10);
        $this->Text(41.5, 135, utf8_decode("CIDADE:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((41.5 + $this->GetStringWidth("CIDADE:") + 2), 135, $cid);

        $this->SetFont("Arial", "B", 10);
        $this->Text(129.4, 135, utf8_decode("ESTADO:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((129.4 + $this->GetStringWidth("ESTADO:") + 2), 135, $est);

        $this->SetFont("Arial", "B", 10);
        $this->Text(24, 139.5, utf8_decode("REPRESENTANTE:"));

        $this->SetFont("Arial", "", 10);
        $this->Text((24 + $this->GetStringWidth("REPRESENTANTE:") + 2), 139.5, $rep);
    }

    function TabelaItens(array $itens)
    {
        $this->SetFont("Arial", "B", 10);
        $this->Text(22, 148, utf8_decode("O(S) SEGUINTE(S) PRODUTO(S):"));

        $this->SetFont("Arial", "BI", 10);
        $this->SetXY(62, 152);
        $this->Cell(12, 5, utf8_decode("Qtde.:"));
        $this->Cell(8, 5, utf8_decode(""));
        $this->Cell(60, 5, utf8_decode("Descrição do Produto:"));

        $peso = 0.0;
        $y = 157;
        foreach ($itens as $item) {
            $pesoUn = $item->getPeso() / 1000;
            $desc = $item->getProduto()->getDescricao();

            $this->SetXY(62, $y);
            $this->SetFont("Arial", "", 10);
            $this->Cell(12, 5, utf8_decode("$pesoUn"));
            $this->SetFont("Arial", "BI", 10);
            $this->Cell(8, 5, utf8_decode("Ton"));
            $this->SetFont("Arial", "", 10);
            $this->Cell(60, 5, utf8_decode("$desc"));

            $peso += $pesoUn;
            $y += 5;
        }

        $this->SetFont("Arial", "BI", 10);
        $this->SetXY(52, $y);
        $this->Cell(10, 5, utf8_decode("Total"), 0, 0, "R");
        $this->Cell(12, 5, utf8_decode("$peso"), "T");
        $this->Cell(8, 5, utf8_decode("Ton"), "T");
    }

    function Observacoes()
    {
        $this->SetFont("Arial", "B", 8);
        $this->Text(18, 189, utf8_decode("OBSERVAÇÃO:"));
    }

    function Mensagem()
    {
        $msg = utf8_decode("CERTOS DE SUAS PROVIDÊNCIAS, ANTECIPAMOS NOSSOS AGRADECIMENTOS.");

        $this->SetFont("Arial", "B", 10);
        $this->Text(((210 - $this->GetStringWidth($msg)) / 2), 232, $msg);
    }

    function Assinatura()
    {
        $empresa = utf8_decode($this->parametrizacao->pessoa->razaoSocial);

        $this->SetXY(12, 269);
        $this->SetFont("Arial", "B", 10);
        $this->Cell($this->GetStringWidth($empresa)+2, 5, $empresa, "T", 0, "C");
    }
}