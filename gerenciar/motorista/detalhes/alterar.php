<?php

use scr\control\MotoristaDetalhesControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $control = new MotoristaDetalhesControl();

    header('Content-type: application/json');
    echo $control->alterar (
        $_POST['endereco'], $_POST['contato'], $_POST['pessoa'], $_POST['dados'], $_POST['motorista'],
        $_POST['nome'], $_POST['rg'], $_POST['cpf'], $_POST['nasc'],
        $_POST['banco'], $_POST['agencia'], $_POST['conta'], $_POST['tipo'],
        $_POST['telefone'], $_POST['celular'], $_POST['email'],
        $_POST['rua'], $_POST['numero'], $_POST['bairro'], $_POST['complemento'], $_POST['cep'], $_POST['cidade']
    );
}