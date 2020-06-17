<?php

require "../../../header.php";

if (!isset($_SESSION["USER_ID"])) {
    header('Location: /login');
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "GET") !== 0) {
    header('Content-type: application/json');
    echo "Método inválido.";
} else {
    header('Content-type: application/json');
    echo (new scr\control\CaminhaoNovoControl())->obterTipos();
}