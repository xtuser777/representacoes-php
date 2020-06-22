<?php

require_once '../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /representacoes/login');
} else {
    $page_title = 'Eventos do Sistema';
    $section_container = '/src/view/inicio/index.php';
    $section_scripts = '/src/view/inicio/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}