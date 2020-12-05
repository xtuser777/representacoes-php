<?php

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Controlar Orçamentos de Frete';
    $section_container = '/src/view/orcamento/frete/index.php';
    $section_scripts = '/src/view/orcamento/frete/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}