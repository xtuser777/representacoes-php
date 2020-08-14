<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Novo Produto';
    $section_container = '/src/view/gerenciar/produto/novo.php';
    $section_scripts = '/src/view/gerenciar/produto/novo_scripts.php';

    require ROOT . '/src/view/layout.php';
}