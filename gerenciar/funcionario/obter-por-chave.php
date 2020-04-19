<?php

require_once '../../header.php';

if (!isset($_SESSION['USER_ID']))
{
    header('Location: /login/index.php');
}
elseif (strcmp($_SESSION['USER_LEVEL'], '1') !== 0)
{
    header('Location: /login/denied.php');
}
elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0)
{
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
}
else
{
    $chave = $_POST['chave'];
    $control = new \scr\control\FuncionarioControl();

    header('Content-type: application/json');
    echo $control->getByKey($chave);
}