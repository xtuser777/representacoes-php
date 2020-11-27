<?php

use \scr\control\InicioControl;

require "../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header("Location: /representacoes/login");
} elseif ($_COOKIE["USER_LEVEL"] !== "1") {
    header("Location: /representacoes/login/denied.php");
} elseif ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Content-type: application/json");
    echo "Método http inválido.";
} else {
    $filtro = $_POST["filtro"];
    $data = $_POST["data"];

    header("Content-type: application/json");
    echo (new InicioControl())->obterPorFiltroData($filtro, $data);
}