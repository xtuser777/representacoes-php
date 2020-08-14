<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Detalhes do Tipo de Caminhão';
    $section_container = '/src/view/gerenciar/tipocaminhao/detalhes.php';
    $section_scripts = '/src/view/gerenciar/tipocaminhao/detalhes_scripts.php';

    require ROOT . '/src/view/layout.php';
}