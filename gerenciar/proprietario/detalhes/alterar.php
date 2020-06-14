<?php

require "../../../header.php";

if (!isset($_SESSION["USER_ID"])) {
    header('Location: /login');
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "POST") !== 0) {
    header('Content-type: application/json');
    echo "Método inválido.";
} else {
    $mot = $_POST["motorista"];
    $tipo = $_POST["tipo"];
    $prp = $_POST["prop"];
    $pes = $_POST["pessoa"];
    $ctt = $_POST["contato"];
    $end = $_POST["endereco"];
    $nome = $_POST["nome"];
    $rg = $_POST["rg"];
    $cpf = $_POST["cpf"];
    $nasc = $_POST["nasc"];
    $rs = $_POST["razaosocial"];
    $nf = $_POST["nomefantasia"];
    $cnpj = $_POST["cnpj"];
    $rua = $_POST["rua"];
    $num = $_POST["numero"];
    $bairro = $_POST["bairro"];
    $comp = $_POST["complemento"];
    $cep = $_POST["cep"];
    $cid = $_POST["cidade"];
    $tel = $_POST["telefone"];
    $cel = $_POST["celular"];
    $email = $_POST["email"];

    header('Content-type: application/json');
    echo (new scr\control\ProprietarioDetalhesControl())->alterar(
        $mot, $tipo, $prp, $pes, $ctt, $end, $nome, $rg, $cpf, $nasc, $rs, $nf, $cnpj, $rua, $num, $bairro, $comp, $cep, $cid, $tel, $cel, $email
    );
}