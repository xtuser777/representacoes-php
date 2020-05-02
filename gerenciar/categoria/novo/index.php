<?php

require_once '../../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login/index.php');
} else {
    $page_title = 'Nova Categoria de Contas';
    $section_container = '/src/view/gerenciar/categoria/novo.php';
    $section_scripts = '/src/view/gerenciar/categoria/novo_scripts.php';

    require ROOT . '/src/view/layout.php';
}