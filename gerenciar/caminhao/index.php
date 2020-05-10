<?php

require_once '../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login');
} else {
    $page_title = 'Gerenciar Caminhões';
    $section_container = '/src/view/gerenciar/caminhao/index.php';
    $section_scripts = '/src/view/gerenciar/caminhao/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}