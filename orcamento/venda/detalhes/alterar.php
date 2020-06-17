<?php

require '../../../header.php';

if (!isset($_SESSION["USER_ID"])) {
    header('Location: /login');
} elseif ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Método não suportado.";
} else {
    $orc = $_POST["orc"];
    $cli = $_POST["cli"];
    $nc = $_POST["nc"];
    $dc = $_POST["dc"];
    $tc = $_POST["tc"];
    $cc = $_POST["cc"];
    $ec = $_POST["ec"];
    $desc = $_POST["desc"];
    $vdd = $_POST["vdd"];
    $cid = $_POST["cid"];
    $peso = str_replace(",", ".", $_POST["peso"]);
    $valor = str_replace(",", ".", $_POST["valor"]);
    $venc = $_POST["venc"];
    $itens = json_decode($_POST["itens"]);

    header('Content-type: application/json');
    echo (new scr\control\OrcamentoVendaDetalhesControl())->alterar(
        $orc, $cli, $nc, $dc, $tc, $cc, $ec, $desc, $vdd, $cid, $peso, $valor, $venc, $itens
    );
}