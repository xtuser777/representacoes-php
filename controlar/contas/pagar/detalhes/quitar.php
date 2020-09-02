<?php

use scr\control\ContasPagarDetalhesControl;

require '../../../../header.php';

if (!isset($_COOKIE["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Método não suportado.";
} else {
    $despesa = $_POST["despesa"];
    $forma = $_POST["forma"];
    $valor = $_POST["valor"];
    $pagamento = $_POST["pagamento"];

    header("Content-type: application/json");
    (new ContasPagarDetalhesControl())->quitar(
        $despesa, $forma, $valor, $pagamento
    );
}
