<?php

require '../../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'Método não suportado pela rota.';
} else {
    $filtro = $_POST['filtro'];

    header('Content-type: application/json');
    echo (new scr\control\PedidoVendaNovoItemControl())->obterPorFiltro($filtro);
}