<?php

use scr\control\MotoristaControl;

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $id = $_POST['id'];
    $control = new MotoristaControl();

    header('Content-type: application/json');
    echo $control->excluir($id);
}