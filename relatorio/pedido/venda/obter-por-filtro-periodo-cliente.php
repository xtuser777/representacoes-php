<?php

use scr\control\RelatorioPedidoVendaControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} elseif ($_COOKIE["USER_LEVEL"] !== "1") {
    header("Location: /representacoes/login/denied");
} else {
    $filtro = $_POST['filtro'];
    $dataInicio = $_POST['inicio'];
    $dataFim = $_POST["fim"];
    $cliente = $_POST["cliente"];
    $ordem = $_POST["ordem"];

    header('Content-type: application/json');
    echo (new RelatorioPedidoVendaControl())->obterPorFiltroPeriodoCliente($filtro, $dataInicio, $dataFim, $cliente, $ordem);
}