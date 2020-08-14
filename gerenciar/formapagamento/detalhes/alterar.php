<?php

use scr\control\FormaPagamentoDetalhesControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $control = new FormaPagamentoDetalhesControl();

    header('Content-type: application/json');
    echo $control->alterar ($_POST['forma'], $_POST['desc'], $_POST['prazo']);
}