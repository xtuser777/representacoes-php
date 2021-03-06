<?php

use scr\control\RelatorioProdutoControl;

require "../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header("Location: /representacoes/login");
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "POST") !== 0) {
    header("Content-type: application/json");
    echo json_encode("Método HTTP inválido.");
} elseif ($_COOKIE["USER_LEVEL"] !== "1") {
    header("Location: /representacoes/login/denied");
} else {
    $filtro = $_POST["filtro"];
    $unidade = $_POST["unidade"];
    $representacao = $_POST["representacao"];
    $ordem = $_POST["ordem"];

    header("Content-type: application/json");
    echo (new RelatorioProdutoControl())->obterPorFiltros($filtro, $unidade, $representacao, $ordem);
}