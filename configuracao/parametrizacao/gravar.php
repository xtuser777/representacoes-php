<?php

use scr\control\ConfiguracaoParametrizacaoControl;

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $control = new ConfiguracaoParametrizacaoControl();

    header('Content-type: application/json');
    echo $control->gravar
    (
        $_POST['razaosocial'], $_POST['nomefantasia'], $_POST['cnpj'],
        $_POST['rua'], $_POST['numero'], $_POST['bairro'], $_POST['complemento'], $_POST['cep'], $_POST['cidade'],
        $_POST['telefone'], $_POST['celular'], $_POST['email']
    );
}