<?php

use scr\control\FuncionarioControl;

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_COOKIE['USER_LEVEL'], '1') !== 0) {
    header('Location: /representacoes/login/denied.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $chave = $_POST['chave'];
    $adm = $_POST['adm'];
    $control = new FuncionarioControl();

    header('Content-type: application/json');
    echo $control->getByKeyAdm($chave,$adm);
}