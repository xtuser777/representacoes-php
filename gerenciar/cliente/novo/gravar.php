<?php

use scr\control\ClienteNovoControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $control = new ClienteNovoControl();

    header('Content-type: application/json');
    echo $control->gravar (
        $_POST['tipo'],
        $_POST['nome'], $_POST['rg'], $_POST['cpf'], $_POST['nasc'],
        $_POST['razaosocial'], $_POST['nomefantasia'], $_POST['cnpj'],
        $_POST['telefone'], $_POST['celular'], $_POST['email'],
        $_POST['rua'], $_POST['numero'], $_POST['bairro'], $_POST['complemento'], $_POST['cep'], $_POST['cidade']
    );
}