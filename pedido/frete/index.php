<?php

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Controlar Pedidos de Frete';
    $section_container = '/src/view/pedido/frete/index.php';
    $section_scripts = '/src/view/pedido/frete/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}