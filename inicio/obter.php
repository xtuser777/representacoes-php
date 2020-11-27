<?php

use scr\control\InicioControl;

require '../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'GET') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $control = new InicioControl();

    header('Content-type: application/json');
    echo $control->obter();
}