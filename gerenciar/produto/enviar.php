<?php

use scr\control\ProdutoControl;

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $id = $_POST['id'];
    $control = new ProdutoControl();

    header('Content-type: application/json');
    echo $control->enviar($id);
}