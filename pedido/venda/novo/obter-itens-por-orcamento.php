<?php

use scr\control\PedidoVendaNovoControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-type: application/json');
    echo 'Método não suportado pela rota.';
} else {
    $orc = $_POST['orc'];

    header('Content-type: application/json');
    echo (new PedidoVendaNovoControl())->obterItensPorOrcamento($orc);
}