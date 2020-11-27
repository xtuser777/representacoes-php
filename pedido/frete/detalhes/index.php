<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} else {
    $page_title = 'Detalhes do Pedido de Frete';
    $section_container = '/src/view/pedido/frete/detalhes.php';
    $section_scripts = '/src/view/pedido/frete/detalhes_scripts.php';

    require ROOT . '/src/view/layout.php';
}