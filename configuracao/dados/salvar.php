<?php

use scr\control\ConfiguracoesDadosControl;

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
} else {
    $control = new ConfiguracoesDadosControl();

    header('Content-type: application/json');
    echo $control->alterar
    (
        $_POST['endereco'], $_POST['contato'], $_POST['pessoa'], $_POST['funcionario'], $_POST['usuario'], $_POST['ativo'],
        $_POST['nome'], $_POST['rg'], $_POST['cpf'], $_POST['nasc'],
        $_POST['adm'], $_POST['tipo'],
        $_POST['rua'], $_POST['numero'], $_POST['bairro'], $_POST['complemento'], $_POST['cep'], $_POST['cidade'],
        $_POST['telefone'], $_POST['celular'], $_POST['email'],
        $_POST['nivel'], $_POST['senha'], $_POST['login']
    );
}