<?php

require '../../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo 'Método não suportado por esta rota.';
} else {
    header('Content-type: application/json');
    echo (new scr\control\PedidoVendaNovoItemControl())->obter();
}