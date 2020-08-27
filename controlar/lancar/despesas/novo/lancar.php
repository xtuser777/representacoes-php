<?php

use scr\control\LancarDespesasNovoControl;

require '../../../../header.php';

if (!isset($_COOKIE["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Método não suportado.";
} else {
    $empresa = $_POST["empresa"];
    $categoria = $_POST["categoria"];
    $pedido = $_POST["pedido"];
    $descricao = $_POST["descricao"];
    $data = $_POST["data"];
    $valor = $_POST["valor"];
    $vencimento = $_POST["vencimento"];

    header("Content-type: application/json");
    (new LancarDespesasNovoControl())->lancar(
        $empresa, $categoria, $pedido, $descricao, $data, $valor, $vencimento
    );
}