<?php

use scr\control\LancarDespesasControl;

require "../../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header("Location: /representacoes/login");
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "POST") !== 0) {
    header("Content-type: application/json");
    echo json_encode("Método HTTP inválido.");
} else {
    $col = $_POST["col"];

    header("Content-type: application/json");
    echo (new LancarDespesasControl())->ordenar($col);
}