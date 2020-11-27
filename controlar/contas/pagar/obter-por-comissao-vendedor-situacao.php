<?php

use scr\control\ContasPagarControl;

require "../../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header("Location: /representacoes/login");
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "POST") !== 0) {
    header("Content-type: application/json");
    echo json_encode("Método HTTP inválido.");
} else {
	$comissao = $_POST["comissao"];
	$vendedor = $_POST["vendedor"];
    $situacao = $_POST["situacao"];
    $ordem = $_POST["ordem"];

    header("Content-type: application/json");
    echo (new ContasPagarControl())->obterPorComissaoVendedorSituacao($situacao, $comissao, $vendedor, $ordem);
}