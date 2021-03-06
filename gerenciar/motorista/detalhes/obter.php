<?php

use scr\control\MotoristaDetalhesControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'GET') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $control = new MotoristaDetalhesControl();

    header('Content-type: application/json');
    echo $control->obter();
}