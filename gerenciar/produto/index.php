<?php

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Gerenciar Produtos';
    $section_container = '/src/view/gerenciar/produto/index.php';
    $section_scripts = '/src/view/gerenciar/produto/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}