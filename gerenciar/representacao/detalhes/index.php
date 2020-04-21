<?php

require_once '../../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login/index.php');
} else {
    $page_title = 'Detalhes da Representação';
    $section_container = '/src/view/gerenciar/representacao/detalhes.php';
    $section_scripts = '/src/view/gerenciar/representacao/detalhes_scripts.php';

    require ROOT . '/src/view/layout.php';
}