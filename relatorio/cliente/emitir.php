<?php

use scr\control\RelatorioClienteControl;

require "../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "GET") !== 0) {
    header('Content-type: application/json');
    echo "Método invalido";
} elseif ($_COOKIE["USER_LEVEL"] !== "1") {
    header("Location: /representacoes/login/denied");
} else {
    $filtro = $_GET["filtro"];
    $inicio = $_GET["inicio"];
    $fim = $_GET["fim"];
    $tipo = $_GET["tipo"];
    $ordem = $_GET["ordem"];

    echo (new RelatorioClienteControl())->gerarRelatorioClientes($filtro, $inicio, $fim, $tipo, $ordem);
}