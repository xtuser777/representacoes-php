<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} else {
    $page_title = 'Detalhes do Orçamento de Frete';
    $section_container = '/src/view/orcamento/frete/detalhes.php';
    $section_scripts = '/src/view/orcamento/frete/detalhes_scripts.php';

    require ROOT . '/src/view/layout.php';
}