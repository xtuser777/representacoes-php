<?php

require '../../../header.php';

if (!isset($_COOKIE["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Método não suportado.";
} else {
    $cli = $_POST["cli"];
    $desc = $_POST["desc"];
    $vdd = $_POST["vdd"];
    $cid = $_POST["cid"];
    $peso = str_replace(",", ".", $_POST["peso"]);
    $valor = str_replace(",", ".", $_POST["valor"]);
    $forma = $_POST["forma"];
    $valorPago = $_POST["valorPago"];
    $itens = json_decode($_POST["itens"]);

    echo (new scr\control\PedidoVendaNovoControl())->gravar(
        $cli, $desc, $vdd, $cid, $peso, $valor, $forma, $valorPago, $itens
    );
}