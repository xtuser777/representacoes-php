<?php

use scr\control\ProdutoControl;

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $chave = $_POST['filtro'];
    $control = new ProdutoControl();

    header('Content-type: application/json');
    echo $control->obterPorChave($chave);
}