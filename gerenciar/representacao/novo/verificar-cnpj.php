<?php

require_once '../../../header.php';

if (!isset($_SESSION['USER_ID']))
{
    header('Location: /login/index.php');
}
elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0)
{
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
}
else
{
    $cnpj = $_POST['cnpj'];
    $control = new \scr\control\RepresentacaoNovoControl();

    header('Content-type: application/json');
    echo $control->verificarCnpj($cnpj);
}