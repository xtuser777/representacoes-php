<?php

require '../../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-type: application/json');
    echo 'Método não suportado por esta rota.';
} else {
    $orc = $_POST["orcamento"];

    header('Content-type: application/json');
    echo (new scr\control\OrcamentoFreteDetalhesItemControl())->obter($orc);
}