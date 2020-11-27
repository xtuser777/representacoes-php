<?php

require "../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header("Location: /representacoes/login");
} else {
    $page_title = "Controlar Pedidos de Venda";
    $section_container = "/src/view/pedido/venda/index.php";
    $section_scripts = "/src/view/pedido/venda/index_scripts.php";

    require ROOT . "/src/view/layout.php";
}