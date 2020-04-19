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
    $cpf = $_POST['cpf'];
    $control = new \scr\control\FuncionarioDetalhesControl();

    header('Content-type: application/json');
    echo $control->verificarCpf($cpf);
}