<?php

use scr\control\LancarDespesasNovoControl;

require "../../../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header("Location: /representacoes/login");
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "GET") !== 0) {
    header("Content-type: application/json");
    echo json_encode("Método HTTP inválido.");
} else {
    header("Content-type: application/json");
    echo (new LancarDespesasNovoControl())->obterCategorias();
}