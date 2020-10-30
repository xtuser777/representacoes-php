<?php

use scr\control\PedidoVendaDetalhesControl;

require "../../../header.php";

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $pedido = $_POST["pedido"];
    $control = new PedidoVendaDetalhesControl();

    header('Content-type: application/json');
    echo $control->obterComissaoVendedor($pedido);
}