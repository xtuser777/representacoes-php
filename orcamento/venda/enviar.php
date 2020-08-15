<?php

use scr\control\OrcamentoVendaControl;

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} else {
    $id = $_POST['id'];
    $control = new OrcamentoVendaControl();

    header('Content-type: application/json');
    echo $control->enviar($id);
}