<?php

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} else {
    $id = $_POST['id'];

    header('Content-type: application/json');
    echo (new scr\control\OrcamentoFreteControl())->enviar($id);
}