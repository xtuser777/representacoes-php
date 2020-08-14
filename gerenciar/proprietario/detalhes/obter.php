<?php

require "../../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER["REQUEST_METHOD"], "GET") !== 0) {
    header('Content-type: application/json');
    echo "Método não suportado.";
} else {
    header('Content-type: application/json');
    echo (new scr\control\ProprietarioDetalhesControl())->obter();
}