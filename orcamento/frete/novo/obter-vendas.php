<?php

require "../../../header.php";

if (!isset($_SESSION["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "GET") !== 0) {
    header('Content-type: application/json');
    echo "Método invalido";
} else {
    header('Content-type: application/json');
    echo (new \scr\control\OrcamentoFreteNovoControl())->obterVendas();
}