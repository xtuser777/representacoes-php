<?php

use scr\control\RelatorioContasPagarControl;

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
    $venc = $_GET["venc"];
    $comissao = $_GET["comissao"];
    $vendedor = $_GET["vendedor"];
    $situacao = $_GET["situacao"];
    $ordem = $_GET["ordem"];

    echo (new RelatorioContasPagarControl())->gerarRelatorioContas($filtro, $inicio, $fim, $venc, $comissao, $vendedor, $situacao, $ordem);
}