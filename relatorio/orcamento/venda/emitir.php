<?php

use scr\control\RelatorioOrcamentoVendaControl;

require "../../../header.php";

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
    $cliente = $_GET["cliente"];
    $ordem = $_GET["ordem"];

    echo (new RelatorioOrcamentoVendaControl())->gerarRelatorioOrcamentos($filtro, $inicio, $fim, $cliente, $ordem);
}