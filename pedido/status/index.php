<?php

require '../../header.php';

if (!isset($_COOKIE["USER_ID"])) {
    header("Location: /representacoes/login");
} else {
    $page_title = 'Alterar Status de Pedidos de Frete';
    $section_container = '/src/view/pedido/status/index.php';
    $section_scripts = '/src/view/pedido/status/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}