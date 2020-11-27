<?php

use scr\control\PedidoFreteNovoControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'GET') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $control = new PedidoFreteNovoControl();
    $id = $_GET["id"];

    header('Content-type: application/json');
    echo $control->obterOrcamento($id);
}