<?php

use scr\control\RelatorioPedidoFreteControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} elseif ($_COOKIE["USER_LEVEL"] !== "1") {
    header("Location: /representacoes/login/denied");
} else {
    $dataInicio = $_POST['inicio'];
    $dataFim = $_POST["fim"];
    $status = $_POST["status"];
    $cliente = $_POST["cliente"];
    $ordem = $_POST["ordem"];

    header('Content-type: application/json');
    echo (new RelatorioPedidoFreteControl())->obterPorPeriodoStatusCliente($dataInicio, $dataFim, $status, $cliente, $ordem);
}