<?php

require_once '../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $col = $_POST['col'];

    header('Content-type: application/json');
    echo (new scr\control\OrcamentoFreteControl())->ordenar($col);
}