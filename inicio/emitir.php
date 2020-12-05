<?php

use scr\control\InicioControl;

require "../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "GET") !== 0) {
    header('Content-type: application/json');
    echo "Método invalido";
} else {
    $filtro = $_GET["filtro"];
    $data = $_GET["data"];
    $tipo = $_GET["tipo"];

    echo (new InicioControl())->gerarRelatorioEventos($filtro, $data, $tipo);
}