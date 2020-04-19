<?php

require_once '../header.php';

if (!isset($_SESSION['USER_ID']))
{
    header('Location: /login/index.php');
}
elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0)
{
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
}
elseif (!isset($_POST['estado']))
{
    header('Content-type: application/json');
    echo json_encode('Parâmetro inválido.');
}
else
{
    $estado = $_POST['estado'];

    $control = new \scr\control\CidadeControl();

    header('Content-type: application/json');
    echo $control->getByEstado($estado);
}