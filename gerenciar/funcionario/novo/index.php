<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} elseif (strcmp($_COOKIE['USER_LEVEL'], '1') !== 0) {
    header('Location: /representacoes/login/denied.php');
} else {
    $page_title = 'Novo Funcionário';
    $section_container = '/src/view/gerenciar/funcionario/novo.php';
    $section_scripts = '/src/view/gerenciar/funcionario/novo_scripts.php';

    require ROOT . '/src/view/layout.php';
}