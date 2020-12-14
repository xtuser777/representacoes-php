<?php

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif ($_COOKIE["USER_LEVEL"] !== "1") {
    header('Location: /representacoes/login/denied');
} else {
    $page_title = 'Relatório de Produtos';
    $section_container = '/src/view/relatorio/produto/index.php';
    $section_scripts = '/src/view/relatorio/produto/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}