<?php

require '../../../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login');
} elseif ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-type: application/json');
    echo 'Método não suportado por esta rota.';
} else {
    $rep = $_POST["representacao"];

    header('Content-type: application/json');
    echo (new scr\control\OrcamentoFreteNovoItemControl())->obter($rep);
}