<?php

use scr\control\CaminhaoControl;

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $col = $_POST['col'];
    $control = new CaminhaoControl();

    header('Content-type: application/json');
    echo $control->ordenar($col);
}