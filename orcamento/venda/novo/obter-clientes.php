<?php

require_once '../../../header.php';

if (!isset($_SESSION['USER_ID']))
{
    header('Location: /login');
}
elseif (strcmp($_SERVER['REQUEST_METHOD'], 'GET') !== 0)
{
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
}
else
{
    $control = new \scr\control\OrcamentoVendaNovoControl();

    header('Content-type: application/json');
    echo $control->obterClientes();
}