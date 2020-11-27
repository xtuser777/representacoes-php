<?php

use scr\control\PedidoFreteNovoControl;

require "../../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "POST") !== 0) {
    header('Content-type: application/json');
    echo "Método inválido";
} else {
    $dist = $_POST["distancia"];
    $eixos = $_POST["eixos"];

    header('Content-type: application/json');
    echo (new PedidoFreteNovoControl())->calcularPisoMinimo($dist, $eixos);
}