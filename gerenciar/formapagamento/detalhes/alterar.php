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
    $control = new \scr\control\FormaPagamentoDetalhesControl();

    header('Content-type: application/json');
    echo $control->alterar ($_POST['forma'], $_POST['desc'], $_POST['prazo']);
}