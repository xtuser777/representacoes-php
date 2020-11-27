<?php

use scr\control\PedidoFreteControl;

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} else {
    $id = $_POST['id'];
    $control = new PedidoFreteControl();

    header('Content-type: application/json');
    echo $control->enviar($id);
}