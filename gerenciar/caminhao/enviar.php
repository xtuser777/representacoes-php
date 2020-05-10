<?php

require_once '../../header.php';

if (!isset($_SESSION['USER_ID']))
{
    header('Location: /login');
}
else
{
    $id = $_POST['id'];
    $control = new \scr\control\CaminhaoControl();

    header('Content-type: application/json');
    echo $control->enviar($id);
}