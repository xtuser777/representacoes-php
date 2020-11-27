<?php

use scr\control\PedidoAutorizarVisualizarControl;

require "../../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "POST") !== 0) {
    header('Content-type: application/json');
    echo "Método inválido.";
} else {
    $etapa = $_POST["etapa"];
    $pedido = $_POST["pedido"];

    header('Content-type: application/json');
    echo (new PedidoAutorizarVisualizarControl())->autorizar($etapa, $pedido);
}