<?php

require_once '../../header.php';

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
    $id = $_POST['id'];
    $control = new \scr\control\ProdutoControl();

    header('Content-type: application/json');
    echo $control->excluir($id);
}