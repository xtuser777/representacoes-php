<?php

use scr\control\FuncionarioDetalhesControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $login = $_POST['login'];
    $control = new FuncionarioDetalhesControl();

    header('Content-type: application/json');
    echo $control->verificarLogin($login);
}