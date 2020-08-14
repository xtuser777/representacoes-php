<?php

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Gerenciar Representações';
    $section_container = '/src/view/gerenciar/representacao/index.php';
    $section_scripts = '/src/view/gerenciar/representacao/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}