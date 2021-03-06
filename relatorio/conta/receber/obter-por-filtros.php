<?php

use scr\control\RelatorioContasReceberControl;

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
    $data1 = $_POST["inicio"];
    $data2 = $_POST["fim"];
    $venc = $_POST["venc"];
    $comissao = $_POST["comissao"];
    $representacao = $_POST["representacao"];
    $situacao = $_POST["situacao"];
    $ordem = $_POST["ordem"];

    header("Content-type: application/json");
    echo (new RelatorioContasReceberControl())->obterPorFiltros($filtro, $data1, $data2, $venc, $comissao, $representacao, $situacao, $ordem);
}