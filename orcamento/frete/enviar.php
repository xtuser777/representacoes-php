<?php

require_once '../../header.php';

if (!isset($_SESSION['USER_ID']))
{
    header('Location: /representacoes/login');
}
else
{
    $id = $_POST['id'];

    header('Content-type: application/json');
    echo (new scr\control\OrcamentoFreteControl())->enviar($id);
}