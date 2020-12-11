<?php

require "../../../header.php";

if (!isset($_COOKIE["USER_ID"])) {
    header("Location: /representacoes/login");
} elseif ($_SERVER["REQUEST_METHOD"] !== "GET") {
    header("Content-type: application/json");
    echo "Método http inválido.";
} else {
    $page_title = "Relatório de Orçamentos de Venda";
    $section_container = "/src/view/relatorio/orcamento/venda/index.php";
    $section_scripts = '/src/view/relatorio/orcamento/venda/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}
