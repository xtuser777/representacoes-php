<?php

require_once '../../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login/index.php');
} else {
    $page_title = 'Novo Produto';
    $section_container = '/src/view/gerenciar/produto/novo.php';
    $section_scripts = '/src/view/gerenciar/produto/novo_scripts.php';

    require ROOT . '/src/view/layout.php';
}