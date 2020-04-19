<?php

require_once '../header.php';

if (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método não suportado.');
}

if (!isset($_POST['login']) || !isset($_POST['senha'])) {
    header('Content-type: application/json');
    echo json_encode('Parâmetros incorretos.');
}

$login = $_POST['login'];
$senha = $_POST['senha'];

$control = new \scr\control\LoginControl();

header('Content-type: application/json');
echo $control->autenticar($login, $senha);