<?php

use scr\control\FuncionarioNovoControl;

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_COOKIE['USER_LEVEL'], '1') !== 0) {
    header('Location: /representacoes/login/denied.php');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $control = new FuncionarioNovoControl();

    header('Content-type: application/json');
    echo $control->gravar
    (
        $_POST['nome'],
        $_POST['rg'],
        $_POST['cpf'],
        $_POST['nasc'],
        $_POST['adm'],
        $_POST['tipo'],
        $_POST['rua'],
        $_POST['numero'],
        $_POST['bairro'],
        $_POST['complemento'],
        $_POST['cep'],
        $_POST['cidade'],
        $_POST['telefone'],
        $_POST['celular'],
        $_POST['email'],
        $_POST['nivel'],
        $_POST['senha'],
        $_POST['login']
    );
}