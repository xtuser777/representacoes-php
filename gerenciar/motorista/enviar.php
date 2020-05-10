<?php

require_once '../../header.php';

if (!isset($_SESSION['USER_ID']))
{
    header('Location: /login/index.php');
}
else
{
    $id = $_POST['id'];
    $control = new \scr\control\MotoristaControl();

    header('Content-type: application/json');
    echo $control->enviar($id);
}