<?php

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Gerenciar Tipos de Caminhão';
    $section_container = '/src/view/gerenciar/tipocaminhao/index.php';
    $section_scripts = '/src/view/gerenciar/tipocaminhao/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}