<?php

require '../../../header.php';

if (!isset($_SESSION["USER_ID"])) {
    header('Location: /login');
} elseif ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Método não suportado.";
} else {
    $cli = $_POST["cli"];
    $nc = $_POST["nc"];
    $dc = $_POST["dc"];
    $tc = $_POST["tc"];
    $cc = $_POST["cc"];
    $ec = $_POST["ec"];
    $desc = $_POST["desc"];
    $vdd = $_POST["vdd"];
    $cid = $_POST["cid"];
    $peso = $_POST["peso"];
    $valor = $_POST["valor"];
    $venc = $_POST["venc"];
    $itens = json_decode($_POST["itens"]);

    echo (new scr\control\OrcamentoVendaNovoControl())->gravar(
        $cli, $nc, $dc, $tc, $cc, $ec, $desc, $vdd, $cid, $peso, $valor, $venc, $itens
    );
}