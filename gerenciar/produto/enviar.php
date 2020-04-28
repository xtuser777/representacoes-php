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
else
{
    $id = $_POST['id'];
    $control = new \scr\control\ProdutoControl();

    header('Content-type: application/json');
    echo $control->enviar($id);
}