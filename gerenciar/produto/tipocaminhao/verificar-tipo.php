<?php

use scr\control\ProdutoTipoCaminhaoControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $tipo = $_POST['tipo'];
    $control = new ProdutoTipoCaminhaoControl();

    header('Content-type: application/json');
    echo $control->verificarTipo($tipo);
}