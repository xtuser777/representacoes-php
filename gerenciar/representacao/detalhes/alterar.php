<?php

use scr\control\RepresentacaoDetalhesControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $control = new RepresentacaoDetalhesControl();

    header('Content-type: application/json');
    echo $control->alterar (
        $_POST['endereco'], $_POST['contato'], $_POST['pessoa'], $_POST['representacao'],
        $_POST['razaosocial'], $_POST['nomefantasia'], $_POST['cnpj'],
        $_POST['telefone'], $_POST['celular'], $_POST['email'],
        $_POST['rua'], $_POST['numero'], $_POST['bairro'], $_POST['complemento'], $_POST['cep'], $_POST['cidade']
    );
}