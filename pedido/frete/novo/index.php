<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} else {
    $page_title = 'Abrir Pedido de Frete';
    $section_container = '/src/view/pedido/frete/novo.php';
    $section_scripts = '/src/view/pedido/frete/novo_scripts.php';

    require ROOT . '/src/view/layout.php';
}