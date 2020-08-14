<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Nova Categoria de Contas';
    $section_container = '/src/view/gerenciar/categoria/novo.php';
    $section_scripts = '/src/view/gerenciar/categoria/novo_scripts.php';

    require ROOT . '/src/view/layout.php';
}