<?php

require '../../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login');
} elseif (strcmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
    header('Content-type: application/json');
    echo json_encode('Método inválido.');
}
else {
    $prod = $_POST['produto'];
    $desc = $_POST["descricao"];
    $med = $_POST["medida"];
    $peso = $_POST["peso"];
    $preco = $_POST["preco"];
    $precoOut = ($_POST["preco_out"] !== '') ? $_POST["preco_out"] : $preco;
    $rep = $_POST["representacao"];

    header('Content-type: application/json');
    echo (new scr\control\ProdutoDetalhesControl())->alterar ($prod, $desc, $med, $peso, $preco, $precoOut, $rep);
}