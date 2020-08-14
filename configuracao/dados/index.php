<?php

require '../../header.php';

if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
} else {
    $page_title = 'Dados do Usuário';
    $section_container = '/src/view/configuracao/dados/index.php';
    $section_scripts = '/src/view/configuracao/dados/index_scripts.php';

    require ROOT . '/src/view/layout.php';
}