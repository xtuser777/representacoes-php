<?php

use scr\control\ContasPagarControl;

require "../../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header("Location: /representacoes/login");
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "POST") !== 0) {
    header("Content-type: application/json");
    echo json_encode("Método HTTP inválido.");
} else {
    $filtro = $_POST["filtro"];
    $data1 = $_POST["dataInicio"];
    $data2 = $_POST["dataFim"];
	$comissao = $_POST["comissao"];
    $ordem = $_POST["ordem"];

    header("Content-type: application/json");
    echo (new ContasPagarControl())->obterPorFiltroPeriodoComissao($filtro, $data1, $data2, $comissao, $ordem);
}