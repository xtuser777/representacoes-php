<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Nova Representação';
    $section_container = '/src/view/gerenciar/representacao/novo.php';
    $section_scripts = '/src/view/gerenciar/representacao/novo_scripts.php';

    require ROOT . '/src/view/layout.php';
}