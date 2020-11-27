<?php

use scr\control\PedidoFreteNovoItemControl;

require '../../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-type: application/json');
    echo 'Método não suportado pela rota.';
} else {
    $rep = $_POST["representacao"];
    $filtro = $_POST['filtro'];

    header('Content-type: application/json');
    echo (new PedidoFreteNovoItemControl())->obterPorFiltro($rep, $filtro);
}