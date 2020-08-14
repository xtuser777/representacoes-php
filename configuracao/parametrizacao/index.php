<?php

require_once '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} elseif (strcmp($_COOKIE['USER_LEVEL'], '1') !== 0) {
    header('Location: /representacoes/login/denied.php');
} else {
    $page_title = 'Parametrização do Sistema';
    $section_container = '/src/view/configuracao/parametrizacao/index.php';
    $section_scripts = '/src/view/configuracao/parametrizacao/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}