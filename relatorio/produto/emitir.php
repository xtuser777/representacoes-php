<?php

use scr\control\RelatorioProdutoControl;

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
    $unidade = $_GET["unidade"];
    $representacao = $_GET["representacao"];
    $ordem = $_GET["ordem"];

    echo (new RelatorioProdutoControl())->gerarRelatorioProdutos($filtro, $unidade, $representacao, $ordem);
}