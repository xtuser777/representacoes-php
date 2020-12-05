<?php

use scr\control\PedidoFreteNovoControl;

require "../../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "POST") !== 0) {
    header('Content-type: application/json');
    echo "Método inválido.";
} else {
    $desc = $_POST["desc"];
    $cli = $_POST["cli"];
    $orc = $_POST["orc"];
    $ven = $_POST["ven"];
    $rep = $_POST["rep"];
    $cid = $_POST["cid"];
    $tip = $_POST["tip"];
    $prop = $_POST["prop"];
    $cam = $_POST["cam"];
    $dist = $_POST["dist"];
    $mot = $_POST["mot"];
    $valorMotorista = $_POST["vm"];
    $valorAdiantamento = strlen($_POST["vam"]) > 0 ? $_POST["vam"] : 0.0;
    $fa = $_POST["fa"];
    $peso = $_POST["peso"];
    $valor = $_POST["valor"];
    $fr = $_POST["fr"];
    $entrega = $_POST["entrega"];
    $itens = json_decode($_POST["itens"]);
    $etapas = json_decode($_POST["etapas"]);

    header('Content-type: application/json');
    echo (new PedidoFreteNovoControl())->gravar(
        $orc, $ven, $rep, $desc, $cli, $cid, $tip, $prop, $cam, $dist, $mot, $valorMotorista, $valorAdiantamento, $fa, $peso, $valor, $fr, $entrega, $itens, $etapas
    );
}