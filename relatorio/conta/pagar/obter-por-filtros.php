<?php

use scr\control\RelatorioContasPagarControl;

require "../../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header("Location: /representacoes/login");
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "POST") !== 0) {
    header("Content-type: application/json");
    echo json_encode("Método HTTP inválido.");
} elseif ($_COOKIE["USER_LEVEL"] !== "1") {
    header("Location: /representacoes/login/denied");
} else {
    $filtro = $_POST["filtro"];
    $inicio = $_POST["inicio"];
    $fim = $_POST["fim"];
    $venc = $_POST["venc"];
    $comissao = $_POST["comissao"];
    $vendedor = $_POST["vendedor"];
    $situacao = $_POST["situacao"];
    $ordem = $_POST["ordem"];

    header("Content-type: application/json");
    echo (new RelatorioContasPagarControl())->obterPorFiltros($filtro, $inicio, $fim, $venc, $comissao, $vendedor, $situacao, $ordem);
}