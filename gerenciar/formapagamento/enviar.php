<?php

use scr\control\FormaPagamentoControl;

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} else {
    $id = $_POST['id'];
    $control = new FormaPagamentoControl();

    header('Content-type: application/json');
    echo $control->enviar($id);
}