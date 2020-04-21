<?php

require_once '../../../header.php';

if (!isset($_SESSION['USER_ID'])) {
    header('Location: /login/index.php');
} else {
    $page_title = 'Adicionar Unidade da Representação';
    $section_container = '/src/view/gerenciar/representacao/addunidade.php';
    $section_scripts = '/src/view/gerenciar/representacao/addunidade_scripts.php';

    require ROOT . '/src/view/layout.php';
}