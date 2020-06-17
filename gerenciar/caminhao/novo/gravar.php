<?php

require_once '../../../header.php';

if (!isset($_SESSION['USER_ID']))
{
    header('Location: /login');
}
elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0)
{
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
}
else
{
    header('Content-type: application/json');
    echo (new scr\control\CaminhaoNovoControl())->gravar(
        $_POST['placa'],$_POST['marca'],$_POST['modelo'],$_POST['cor'],
        $_POST['anofab'],$_POST['anomod'],$_POST['tipo'],$_POST['proprietario']
    );
}