<?php

use scr\control\PedidoAutorizarVisualizarControl;

require "../../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "GET") !== 0) {
    header('Content-type: application/json');
    echo "Método invalido";
} else {
    $pedido = $_GET["pedido"];
    $etapa = $_GET["etapa"];

    echo (new PedidoAutorizarVisualizarControl())->gerarDocumentoAutorizacao($pedido, $etapa);
}