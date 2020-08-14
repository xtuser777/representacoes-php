<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Novo Motorista';
    $section_container = '/src/view/gerenciar/motorista/novo.php';
    $section_scripts = '/src/view/gerenciar/motorista/novo_scripts.php';

    require ROOT . '/src/view/layout.php';
}