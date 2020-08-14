<?php

require '../../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login/index.php');
} else {
    $page_title = 'Adicionar Unidade da Representação';
    $section_container = '/src/view/gerenciar/representacao/addunidade.php';
    $section_scripts = '/src/view/gerenciar/representacao/addunidade_scripts.php';

    require ROOT . '/src/view/layout.php';
}