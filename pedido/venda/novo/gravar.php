<?php

use scr\control\PedidoVendaNovoControl;

require '../../../header.php';

if (!isset($_COOKIE["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Método não suportado.";
} else {
    $cli = $_POST["cli"];
    $orc = $_POST["orc"];
    $desc = $_POST["desc"];
    $vdd = $_POST["vdd"];
    $cid = $_POST["cid"];
    $peso = str_replace(",", ".", $_POST["peso"]);
    $valor = str_replace(",", ".", $_POST["valor"]);
    $forma = $_POST["forma"];
    $valorPago = $_POST["valorPago"];
    $porcComissaoVendedor = $_POST["porcVdd"];
    $comissoes = json_decode($_POST["comissoes"]);
    $itens = json_decode($_POST["itens"]);

    echo (new PedidoVendaNovoControl())->gravar(
        $cli, $orc, $desc, $vdd, $cid, $peso, $valor, $forma, $valorPago, $porcComissaoVendedor, $comissoes, $itens
    );
}