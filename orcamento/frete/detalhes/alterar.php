<?php

require "../../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "POST") !== 0) {
    header('Content-type: application/json');
    echo "Método inválido.";
} else {
    $orc = $_POST["orc"];
    $desc = $_POST["desc"];
    $ven = $_POST["ven"];
    $rep = $_POST["rep"];
    $cli = $_POST["cli"];
    $cid = $_POST["cid"];
    $tip = $_POST["tip"];
    $dist = $_POST["dist"];
    $peso = $_POST["peso"];
    $valor = $_POST["valor"];
    $entrega = $_POST["entrega"];
    $venc = $_POST["venc"];
    $itens = json_decode($_POST["itens"]);

    header('Content-type: application/json');
    echo (new scr\control\OrcamentoFreteDetalhesControl())->alterar(
        $orc, $desc, $ven, $rep, $cli, $cid, $tip, $dist, $peso, $valor, $entrega, $venc, $itens
    );
}