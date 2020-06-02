<?php

require '../../../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login');
} elseif ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo 'Método não suportado por esta rota.';
} else {
    header('Content-type: application/json');
    echo (new scr\control\OrcamentoVendaNovoItemControl())->obter();
}