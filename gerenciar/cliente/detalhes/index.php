<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Detalhes do Cliente';
    $section_container = '/src/view/gerenciar/cliente/detalhes.php';
    $section_scripts = '/src/view/gerenciar/cliente/detalhes_scripts.php';

    require ROOT . '/src/view/layout.php';
}