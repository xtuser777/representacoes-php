<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} else {
    $page_title = 'Detalhes do Orçamento de Venda';
    $section_container = '/src/view/orcamento/venda/detalhes.php';
    $section_scripts = '/src/view/orcamento/venda/detalhes_scripts.php';

    require ROOT . '/src/view/layout.php';
}