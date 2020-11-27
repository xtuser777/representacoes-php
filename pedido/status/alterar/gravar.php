<?php

use scr\control\PedidoStatusAlterarControl;

require "../../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "POST") !== 0) {
    header('Content-type: application/json');
    echo "Método inválido.";
} else {
    $pedido = $_POST["pedido"];
    $status = $_POST["status"];
    $data = $_POST["data"];
    $obs = $_POST["obs"];

    header('Content-type: application/json');
    echo (new PedidoStatusAlterarControl())->gravar(
        $pedido, $status, $data, $obs
    );
}