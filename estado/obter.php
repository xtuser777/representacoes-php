<?php

use scr\control\EstadoControl;

require '../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'GET') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $control = new EstadoControl();

    header('Content-type: application/json; charset=UTF-8');
    echo $control->getAll();
}