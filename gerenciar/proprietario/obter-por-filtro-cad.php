<?php

require '../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $filtro = $_POST['filtro'];
    $cad = $_POST['cad'];

    header('Content-type: application/json');
    echo (new scr\control\ProprietarioControl())->obterPorFiltroCadastro($filtro, $cad);
}