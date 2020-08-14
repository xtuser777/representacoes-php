<?php

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} else {
    $page_title = 'Gerenciar Categorias de Contas';
    $section_container = '/src/view/gerenciar/categoria/index.php';
    $section_scripts = '/src/view/gerenciar/categoria/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}