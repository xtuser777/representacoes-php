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
    $chave = $_POST['chave'];
    $cad = $_POST['cad'];
    $control = new \scr\control\RepresentacaoControl();

    header('Content-type: application/json');
    echo $control->obterPorChaveCad($chave, $cad);
}