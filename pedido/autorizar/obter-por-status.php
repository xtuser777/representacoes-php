<?php

use scr\control\PedidoAutorizarControl;

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $status = $_POST["status"];
    $ordem = $_POST["ordem"];

    header('Content-type: application/json');
    echo (new PedidoAutorizarControl())->obterPorStatus($status, $ordem);
}