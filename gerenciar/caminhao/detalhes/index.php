<?php

require_once '../../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login');
} else {
    $page_title = 'Detalhes do Caminhão';
    $section_container = '/src/view/gerenciar/caminhao/detalhes.php';
    $section_scripts = '/src/view/gerenciar/caminhao/detalhes_scripts.php';

    require ROOT . '/src/view/layout.php';
}