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
    $login = $_POST['login'];
    $control = new \scr\control\ConfiguracoesDadosControl();

    header('Content-type: application/json');
    echo $control->verificarLogin($login);
}