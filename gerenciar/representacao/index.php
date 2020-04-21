<?php

require_once '../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login/index.php');
} else {
    $page_title = 'Gerenciar Representações';
    $section_container = '/src/view/gerenciar/representacao/index.php';
    $section_scripts = '/src/view/gerenciar/representacao/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}