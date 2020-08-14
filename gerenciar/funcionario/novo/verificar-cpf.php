<?php

use scr\control\FuncionarioNovoControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $cpf = $_POST['cpf'];
    $control = new FuncionarioNovoControl();

    header('Content-type: application/json');
    echo $control->verificarCpf($cpf);
}