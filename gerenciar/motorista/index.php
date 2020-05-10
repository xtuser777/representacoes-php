<?php

require_once '../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login/index.php');
} else {
    $page_title = 'Gerenciar Motoristas';
    $section_container = '/src/view/gerenciar/motorista/index.php';
    $section_scripts = '/src/view/gerenciar/motorista/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}