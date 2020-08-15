<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $email = $_POST['email'];
    $control = new scr\control\OrcamentoVendaNovoControl();

    header('Content-type: application/json');
    echo $control->validarEmail($email);
}