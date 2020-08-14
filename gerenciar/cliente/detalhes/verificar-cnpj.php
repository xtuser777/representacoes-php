<?php

use scr\control\ClienteDetalhesControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $cnpj = $_POST['cnpj'];
    $control = new ClienteDetalhesControl();

    header('Content-type: application/json');
    echo $control->verificar_cnpj($cnpj);
}