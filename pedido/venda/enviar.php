<?php

use scr\control\PedidoVendaControl;

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} else {
    $id = $_POST['id'];
    $control = new PedidoVendaControl();

    header('Content-type: application/json');
    echo $control->enviar($id);
}