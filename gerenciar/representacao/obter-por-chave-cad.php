<?php

use scr\control\RepresentacaoControl;

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $chave = $_POST['chave'];
    $cad = $_POST['cad'];
    $control = new RepresentacaoControl();

    header('Content-type: application/json');
    echo $control->obterPorChaveCad($chave, $cad);
}