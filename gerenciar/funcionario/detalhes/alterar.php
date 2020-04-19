<?php

require_once '../../../header.php';

if (!isset($_SESSION['USER_ID']))
{
    header('Location: /login/index.php');
}
elseif (strcmp($_SESSION['USER_LEVEL'], '1') !== 0)
{
    header('Location: /login/denied.php');
}
elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0)
{
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
}
else
{
    $control = new \scr\control\FuncionarioDetalhesControl();

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