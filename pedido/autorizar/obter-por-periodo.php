<?php

use scr\control\PedidoAutorizarControl;

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $dataInicio = $_POST['inicio'];
    $dataFim = $_POST["fim"];
    $ordem = $_POST["ordem"];

    header('Content-type: application/json');
    echo (new PedidoAutorizarControl())->obterPorPeriodo($dataInicio, $dataFim, $ordem);
}