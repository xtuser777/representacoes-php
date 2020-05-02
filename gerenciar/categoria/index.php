<?php

require_once '../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login');
} else {
    $page_title = 'Gerenciar Categorias de Contas';
    $section_container = '/src/view/gerenciar/categoria/index.php';
    $section_scripts = '/src/view/gerenciar/categoria/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}