<?php

use scr\control\ContasReceberDetalhesControl;

require '../../../../header.php';

if (!isset($_COOKIE["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Método não suportado.";
} else {
    $despesa = $_POST["despesa"];
    $forma = $_POST["forma"];
    $valor = $_POST["valor"];
    $recebimento = $_POST["recebimento"];

    header("Content-type: application/json");
    (new ContasReceberDetalhesControl())->receber(
        $despesa, $forma, $valor, $recebimento
    );
}
