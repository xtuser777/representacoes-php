<?php

use scr\control\CategoriaDetalhesControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $control = new CategoriaDetalhesControl();

    header('Content-type: application/json');
    echo $control->alterar ($_POST['categoria'], $_POST['desc']);
}