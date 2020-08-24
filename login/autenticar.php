<?php

use scr\control\LoginControl;

require '../header.php';

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

$control = new LoginControl();
$user = $control->autenticar($login, $senha);
$response = [];

if ($user) {
    setcookie("USER_ID", $user["id"], time() + (24 * 3600), "/", "", 0, 1);
    setcookie("USER_LOGIN", strtoupper($user["login"]), time() + (24 * 3600), "/", "", 0, 1);
    setcookie("USER_LEVEL", $user["nivel"]["id"], time() + (24 * 3600), "/", "", 0, 1);

    $response = [
        "status" => true,
        "login" => $user["login"]
    ];
} else {
    $response = [
        "status" => false,
        "login" => ""
    ];
}

header('Content-type: application/json');
echo json_encode($response);