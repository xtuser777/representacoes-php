<?php

require '../../../header.php';

if (!isset($_COOKIE["USER_ID"])) {
    header("Location: /representacoes/login");
} else {
    $page_title = 'Alterar Status do Pedido';
    $section_container = '/src/view/pedido/status/alterar.php';
    $section_scripts = '/src/view/pedido/status/alterar_scripts.php';

    require ROOT . '/src/view/layout.php';
}