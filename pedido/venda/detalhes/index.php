<?php

require '../../../header.php';

if (!isset($_COOKIE["USER_ID"])) {
    header("Location: /representacoes/login");
} else {
    $page_title = "Detalhes do Pedido de Venda";
    $section_container = "/src/view/pedido/venda/detalhes.php";
    $section_scripts = "/src/view/pedido/venda/detalhes_scripts.php";

    require ROOT . "/src/view/layout.php";
}