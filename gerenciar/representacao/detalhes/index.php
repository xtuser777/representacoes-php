<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Detalhes da Representação';
    $section_container = '/src/view/gerenciar/representacao/detalhes.php';
    $section_scripts = '/src/view/gerenciar/representacao/detalhes_scripts.php';

    require ROOT . '/src/view/layout.php';
}