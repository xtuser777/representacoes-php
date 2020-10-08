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
    $conta = $_POST["conta"];
    $descricao = $_POST["descricao"];
    $tipo = $_POST["tipo"];
    $forma = $_POST["forma"];
    $intervalo = $_POST["intervalo"];
    $frequencia = $_POST["frequencia"];
    $data = $_POST["data"];
    $valorPago = $_POST["valorPago"];
    $parcelas = $_POST["parcelas"];
    $valor = $_POST["valor"];
    $vencimento = $_POST["vencimento"];

    header("Content-type: application/json");
    echo (new LancarDespesasNovoControl())->lancar(
        $empresa,
        $categoria,
        $pedido,
        $conta,
        $descricao,
        $tipo,
        $forma,
        $intervalo,
        $frequencia,
        $data,
        $valorPago,
        $parcelas,
        $valor,
        $vencimento
    );
}