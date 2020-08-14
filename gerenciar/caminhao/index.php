<?php

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} else {
    $page_title = 'Gerenciar Caminhões';
    $section_container = '/src/view/gerenciar/caminhao/index.php';
    $section_scripts = '/src/view/gerenciar/caminhao/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}