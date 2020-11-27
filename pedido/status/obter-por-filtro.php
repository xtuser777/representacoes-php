<?php

use scr\control\PedidoStatusControl;

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $filtro = $_POST['filtro'];
    $ordem = $_POST["ordem"];

    header('Content-type: application/json');
    echo (new PedidoStatusControl())->obterPorFiltro($filtro, $ordem);
}