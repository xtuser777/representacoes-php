<?php

require '../../../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login');
} elseif ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'Método não suportado pela rota.';
} else {
    $filtro = $_POST['filtro'];

    header('Content-type: application/json');
    echo (new scr\control\OrcamentoVendaDetalhesItemControl())->obterProdutosPorFiltro($filtro);
}