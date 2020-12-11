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
    $data1 = $_POST["inicio"];
    $data2 = $_POST["fim"];
    $venc = $_POST["venc"];
    $situacao = $_POST["situacao"];
    $ordem = $_POST["ordem"];

    header("Content-type: application/json");
    echo (new RelatorioContasPagarControl())->obterPorPeriodoVencimentoSituacao($data1, $data2, $venc, $situacao, $ordem);
}