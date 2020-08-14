<?php

use scr\control\FormaPagamentoNovoControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $control = new FormaPagamentoNovoControl();

    header('Content-type: application/json');
    echo $control->gravar ($_POST['desc'], $_POST['prazo']);
}