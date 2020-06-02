<?php

require '../../../header.php';

if (!isset($_SESSION["USER_ID"])) {
    header('Location: /login');
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "GET") !== 0) {
    echo "Método não suportado por esta rota.";
} else {
    header('Content-type: application/json');

    echo (new scr\control\OrcamentoVendaDetalhesControl())->obter();
}