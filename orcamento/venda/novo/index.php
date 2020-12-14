<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} else {
    $page_title = 'Abrir Orçamento de Venda';
    $section_container = '/src/view/orcamento/venda/novo.php';
    $section_scripts = '/src/view/orcamento/venda/novo_scripts.php';

    require ROOT . '/src/view/layout.php';
}