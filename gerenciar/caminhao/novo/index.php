<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Novo Caminhão';
    $section_container = '/src/view/gerenciar/caminhao/novo.php';
    $section_scripts = '/src/view/gerenciar/caminhao/novo_scripts.php';

    require ROOT . '/src/view/layout.php';
}