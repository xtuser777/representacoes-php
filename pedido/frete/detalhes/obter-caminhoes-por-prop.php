<?php

use scr\control\PedidoFreteDetalhesControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $control = new PedidoFreteDetalhesControl();
    $prop = $_POST["prop"];

    header('Content-type: application/json');
    echo $control->obterCaminhoesPorProp($prop);
}