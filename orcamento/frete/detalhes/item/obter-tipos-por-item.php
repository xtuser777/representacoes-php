<?php

require '../../../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-type: application/json');
    echo 'Método não suportado pela rota.';
} else {
    $item = $_POST['item'];

    header('Content-type: application/json');
    echo (new scr\control\OrcamentoFreteDetalhesItemControl())->obterTiposPorItem($item);
}