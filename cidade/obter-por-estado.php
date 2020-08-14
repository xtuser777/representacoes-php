<?php

use scr\control\CidadeControl;

require '../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} elseif (!isset($_POST['estado'])) {
    header('Content-type: application/json');
    echo json_encode('Parâmetro inválido.');
} else {
    $estado = $_POST['estado'];

    $control = new CidadeControl();

    header('Content-type: application/json');
    echo $control->getByEstado($estado);
}